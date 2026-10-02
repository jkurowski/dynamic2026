/* Edycja stałych sekcji frontu (config/sekcje.php).

   Na froncie: sekcja z atrybutem data-sekcja (wypisuje go $s->edycja() tylko zalogowanym
   z uprawnieniem sekcja-edit) dostaje przycisk „Edytuj sekcję”. Klik ładuje formularz
   z panelu (/admin/sekcje/{klucz}/formularz) do modala, zapis idzie AJAX-em, a po zapisie
   podmieniamy samą sekcję HTML-em pobranym ponownie z tej samej strony - bez przeładowania.

   Przyciski nie siedzą w sekcji, tylko w osobnej warstwie na body (pozycja z getBoundingClientRect),
   żeby nie ruszać styli sekcji (position, overflow, flex).

   W panelu (admin/sekcje/{klucz}/edytuj) używamy tylko sekcjaEdytor() - TinyMCE na polach html. */

(function () {
	'use strict';

	var ADRES = '/admin/sekcje/';

	// TinyMCE na polach typu html w podanym kontenerze
	window.sekcjaEdytor = function (kontener) {
		if (!window.tinymce) {
			return;
		}
		kontener.querySelectorAll('textarea.sekcja-edytor').forEach(function (pole) {
			var stary = tinymce.get(pole.id);
			if (stary) {
				stary.remove();
			}
			tinymce.init({
				target: pole,
				language: 'pl',
				skin: 'oxide',
				branding: false,
				menubar: false,
				statusbar: false,
				height: 260,
				plugins: 'link lists',
				toolbar: 'bold italic | bullist numlist | link | removeformat',
				relative_urls: false,
				entity_encoding: 'raw'
			});
		});
	};

	var sekcje = document.querySelectorAll('[data-sekcja]');
	if (!sekcje.length || !window.bootstrap) {
		return;
	}

	var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	var warstwa = document.createElement('div');
	warstwa.className = 'sekcja-warstwa';
	document.body.appendChild(warstwa);

	var modalEl = document.getElementById('sekcjaModal');
	// focus: false - pułapka fokusu Bootstrapa blokowałaby pola w okienkach TinyMCE (np. wstaw link)
	var modal = new bootstrap.Modal(modalEl, {focus: false});
	var tresc = modalEl.querySelector('.modal-body');
	var tytul = modalEl.querySelector('.modal-title');

	function przyciski() {
		warstwa.innerHTML = '';
		document.querySelectorAll('[data-sekcja]').forEach(function (sekcja) {
			var przycisk = document.createElement('button');
			przycisk.type = 'button';
			przycisk.className = 'sekcja-przycisk';
			przycisk.textContent = 'Edytuj sekcję';
			przycisk.title = sekcja.getAttribute('data-sekcja-nazwa') || '';
			przycisk.addEventListener('click', function () {
				otworz(sekcja.getAttribute('data-sekcja'));
			});
			przycisk.addEventListener('mouseenter', function () { sekcja.classList.add('sekcja-podswietlona'); });
			przycisk.addEventListener('mouseleave', function () { sekcja.classList.remove('sekcja-podswietlona'); });
			przycisk._sekcja = sekcja;
			warstwa.appendChild(przycisk);
		});
		ustaw();
	}

	function ustaw() {
		warstwa.querySelectorAll('.sekcja-przycisk').forEach(function (przycisk) {
			var r = przycisk._sekcja.getBoundingClientRect();
			przycisk.style.top = (r.top + window.scrollY + 12) + 'px';
			przycisk.style.left = (r.right + window.scrollX - przycisk.offsetWidth - 12) + 'px';
		});
	}

	function bledy(lista) {
		var pole = tresc.querySelector('.sekcja-bledy');
		if (!pole) {
			return;
		}
		pole.innerHTML = '';
		lista.forEach(function (tekst) {
			var linia = document.createElement('div');
			linia.textContent = tekst;
			pole.appendChild(linia);
		});
		pole.classList.toggle('d-none', !lista.length);
		if (lista.length) {
			pole.scrollIntoView({block: 'nearest'});
		}
	}

	function otworz(klucz) {
		tytul.textContent = 'Edycja sekcji';
		tresc.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"></div></div>';
		modal.show();

		fetch(ADRES + encodeURIComponent(klucz) + '/formularz', {headers: {'X-Requested-With': 'XMLHttpRequest'}, credentials: 'same-origin'})
			.then(function (odp) {
				if (!odp.ok) {
					throw new Error(odp.status === 403 ? 'Brak uprawnień do edycji sekcji.' : 'Nie udało się wczytać formularza (' + odp.status + ').');
				}
				return odp.text();
			})
			.then(function (html) {
				tresc.innerHTML = html;
				var formularz = tresc.querySelector('form');
				tytul.textContent = formularz.getAttribute('data-nazwa');
				sekcjaEdytor(tresc);
				formularz.addEventListener('submit', function (e) {
					e.preventDefault();
					zapisz(formularz, klucz);
				});
			})
			.catch(function (blad) {
				tresc.innerHTML = '<div class="alert alert-danger mb-0"></div>';
				tresc.firstChild.textContent = blad.message;
			});
	}

	function zapisz(formularz, klucz) {
		if (window.tinymce) {
			tinymce.triggerSave();
		}
		var przycisk = formularz.querySelector('[type="submit"]');
		przycisk.disabled = true;
		przycisk.textContent = 'Zapisywanie...';
		bledy([]);

		fetch(formularz.action, {
			method: 'POST',
			body: new FormData(formularz),
			credentials: 'same-origin',
			headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token}
		})
			.then(function (odp) {
				return odp.json().catch(function () { return {}; }).then(function (dane) {
					if (odp.status === 422) {
						throw dane.bledy || ['Popraw dane w formularzu.'];
					}
					if (!odp.ok) {
						throw [dane.message || 'Nie udało się zapisać (' + odp.status + ').'];
					}
				});
			})
			.then(function () {
				return odswiez(klucz);
			})
			.then(function () {
				modal.hide();
			})
			.catch(function (lista) {
				bledy(Array.isArray(lista) ? lista : [String(lista && lista.message || lista)]);
			})
			.finally(function () {
				przycisk.disabled = false;
				przycisk.textContent = 'Zapisz';
			});
	}

	// Świeży HTML sekcji z tej samej strony - widok renderuje serwer, JS nie zna wyglądu sekcji
	function odswiez(klucz) {
		return fetch(window.location.href, {credentials: 'same-origin', cache: 'no-store'})
			.then(function (odp) { return odp.text(); })
			.then(function (html) {
				var selektor = '[data-sekcja="' + klucz + '"]';
				var nowa = new DOMParser().parseFromString(html, 'text/html').querySelector(selektor);
				var stara = document.querySelector(selektor);
				if (!nowa || !stara) {
					window.location.reload();
					return;
				}
				// animacje.js pokazuje elementy raz, przy wjeździe w kadr - podmieniona sekcja jest już na ekranie
				nowa.querySelectorAll('.pojawia-sie, .rozjasnia-sie, .kolejno').forEach(function (el) {
					el.classList.add('widoczny');
				});
				stara.replaceWith(document.importNode(nowa, true));
				przyciski();
			});
	}

	modalEl.addEventListener('hidden.bs.modal', function () {
		if (window.tinymce) {
			tinymce.remove('#sekcjaModal textarea');
		}
		tresc.innerHTML = '';
	});

	przyciski();
	window.addEventListener('resize', ustaw);
	window.addEventListener('load', ustaw);
	// Obrazki/fonty doładowujące się później przesuwają sekcje
	setInterval(ustaw, 1500);
})();
