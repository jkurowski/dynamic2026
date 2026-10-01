/* Drobna obsługa wspólna dla całego serwisu: wyszukiwarka, zakładki biur,
   filtry wyników, przełącznik motywu i odsłaniana stopka. */

(function ($) {
	'use strict';

	function ustawUkrytePole($formularz, pole, wartosc) {
		var $ukryte = $formularz.find('input[type="hidden"][name="' + pole + '"]');
		if (!$ukryte.length) {
			$ukryte = $('<input type="hidden">').attr('name', pole).appendTo($formularz);
		}
		$ukryte.val(wartosc);
	}

	// --- wyszukiwarka: wybór wartości z listy rozwijanej ---
	$('.pasek-wyszukiwarki').on('click', '.dropdown-item', function () {
		var $pozycja = $(this);
		var $lista = $pozycja.closest('.lista-filtru');
		var $filtr = $lista.prev('.filtr');
		var pole = $filtr.data('pole');

		$lista.find('.dropdown-item').removeClass('wybrany');
		$pozycja.addClass('wybrany');
		$filtr.find('.wartosc').text($pozycja.text());

		ustawUkrytePole($filtr.closest('form'), pole, $pozycja.text());
	});

	// --- zakładki biur w sekcji kontakt ---
	$('.zakladki-biur').on('click', 'button', function () {
		var biuro = $(this).data('biuro');

		$('.zakladki-biur button').removeClass('aktywna');
		$(this).addClass('aktywna');

		$('.dane-biura').removeClass('widoczne');
		$('.dane-biura[data-biuro="' + biuro + '"]').addClass('widoczne');
	});

	// --- filtry na podstronie wyników wyszukiwania ---
	var polaWynikow = ['pokoje', 'powierzchnia', 'pietro', 'status', 'cena'];

	$('.filtry-wynikow, .filtry-inwestycji').each(function () {
		$(this).find('.pole-filtru').each(function (indeks) {
			$(this).attr('data-pole', polaWynikow[indeks]);
		});
	});

	function ustawTekstFiltru($przycisk, tekst) {
		var wezel = $przycisk.contents().filter(function () {
			return this.nodeType === 3;
		}).first()[0];
		if (wezel) {
			wezel.nodeValue = tekst;
		} else {
			$przycisk.prepend(document.createTextNode(tekst));
		}
	}

	$('.filtry-wynikow, .filtry-inwestycji').on('click', '.dropdown-item', function () {
		var $pozycja = $(this);
		var $lista = $pozycja.closest('.lista-filtru');
		var $przycisk = $lista.prev('.pole-filtru');
		var pole = $przycisk.data('pole');

		$lista.find('.dropdown-item').removeClass('wybrany');
		$pozycja.addClass('wybrany');
		ustawTekstFiltru($przycisk, $pozycja.text());
		ustawUkrytePole($przycisk.closest('form'), pole, $pozycja.text());
		filtrujWyniki();
	});

	$('.pasek-wynikow').on('click', '.lista-sortowania .dropdown-item', function () {
		var $pozycja = $(this);
		var $lista = $pozycja.closest('.lista-sortowania');
		$lista.find('.dropdown-item').removeClass('wybrany');
		$pozycja.addClass('wybrany');
		$lista.siblings('.pole-sortowania').find('.wybrane-sortowanie').text($pozycja.text());
		sortujWyniki($pozycja.text().trim());
	});

	// Parametry wybrane na stronie głównej wracają do właściwych pól po przejściu na wyniki.
	// Przyjmujemy tylko wartości, które naprawdę istnieją na liście.
	var parametryAdresu = new URLSearchParams(window.location.search);
	$('.filtry-wynikow').each(function () {
		var $formularz = $(this);
		$formularz.find('.pole-filtru').each(function () {
			var $przycisk = $(this);
			var pole = $przycisk.data('pole');
			var wartosc = parametryAdresu.get(pole);
			if (!wartosc) return;

			var $lista = $przycisk.next('.lista-filtru');
			var $pasujaca = $lista.find('.dropdown-item').filter(function () {
				return $(this).text().trim() === wartosc;
			}).first();
			if (!$pasujaca.length) return;

			$lista.find('.dropdown-item').removeClass('wybrany');
			$pasujaca.addClass('wybrany');
			ustawTekstFiltru($przycisk, wartosc);
			ustawUkrytePole($formularz, pole, wartosc);
		});

		var inwestycja = parametryAdresu.get('inwestycja');
		if (inwestycja && inwestycja !== 'Wszystkie inwestycje') {
			ustawUkrytePole($formularz, 'inwestycja', inwestycja);
		}
	});

	// "Szukaj" nie przeładowuje strony: wyniki są już przefiltrowane, a adres dostaje
	// parametry, żeby dało się go skopiować z tym samym wyborem
	$('.filtry-wynikow').on('submit', function (e) {
		e.preventDefault();
		var parametry = new URLSearchParams(window.location.search);
		$(this).find('input[type="hidden"]').each(function () {
			if (this.value) {
				parametry.set(this.name, this.value);
			}
		});
		if (window.history && history.replaceState) {
			history.replaceState(null, '', '?' + parametry.toString());
		}
		filtrujWyniki();
	});

	// --- rozwijana lista inwestycji w menu ---
	var inwestycjeMenu = [
		['Dom Hygge Twin', 'inwestycje.html#dom-hygge-twin'],
		['Konstancin Riverside House', 'inwestycje.html#konstancin-riverside-house'],
		['Segmenty Lake Village', 'inwestycje.html#segmenty-lake-village'],
		['Zielona Polana', 'inwestycje.html#zielona-polana'],
	];

	function zbudujPodmenu($lista, id) {
		var $pozycja = $lista.children('li').first();
		var $link = $pozycja.children('a').first();
		if (!$pozycja.length || !$link.length || $pozycja.hasClass('pozycja-inwestycje')) {
			return;
		}

		var $daszek = $link.children('.daszek').detach();
		if (!$daszek.length) {
			$daszek = $('<svg class="daszek" viewBox="0 0 16 9" aria-hidden="true">' +
				'<path d="M8 6.65 14.2.32c.42-.43 1.06-.42 1.48.02.42.43.42 1.07 0 1.51L9.21 8.48A1.67 1.67 0 0 1 8 9c-.45 0-.85-.17-1.2-.52L.31 1.85A1.08 1.08 0 0 1 .33.32 1.02 1.02 0 0 1 1.8.32Z" fill="#DC5C0C"/></svg>');
		}

		var $przelacznik = $('<button class="przelacznik-inwestycji" type="button" ' +
			'aria-expanded="false" aria-controls="' + id + '" ' +
			'aria-label="Rozwiń listę inwestycji"></button>').append($daszek);
		// Bez atrybutu hidden
		var $podmenu = $('<ul class="podmenu-inwestycji" id="' + id + '"></ul>');
		inwestycjeMenu.forEach(function (inwestycja) {
			$podmenu.append($('<li></li>').append($('<a></a>', {
				href: inwestycja[1],
				text: inwestycja[0],
			})));
		});

		$pozycja.addClass('pozycja-inwestycje');
		$link.after($przelacznik);
		$pozycja.append($podmenu);
	}

	var $listaMenuMobilnego = $('.menu-mobilne .offcanvas-body > ul');
	zbudujPodmenu($('.menu-glowne > ul'), 'podmenuInwestycji');
	zbudujPodmenu($listaMenuMobilnego, 'podmenuInwestycjiMobilne');

	if ($listaMenuMobilnego.length && !$listaMenuMobilnego.children('[data-uzupelnienie-menu]').length) {
		$listaMenuMobilnego.prepend(
			'<li data-uzupelnienie-menu><a href="index.html">Strona główna</a></li>');
		var $poznajNas = $listaMenuMobilnego.find('a[href="poznaj-nas.html"]').parent();
		$poznajNas.before(
			'<li data-uzupelnienie-menu><a href="aktualnosci.html">Aktualności</a></li>');
	}

	function zamknijPodmenu($przelacznik) {
		$przelacznik.attr({
			'aria-expanded': 'false',
			'aria-label': 'Rozwiń listę inwestycji',
		});
		$('#' + $przelacznik.attr('aria-controls')).removeClass('otwarte');
	}

	$(document).on('click', '.przelacznik-inwestycji', function (zdarzenie) {
		zdarzenie.stopPropagation();
		var $ten = $(this);
		var otwarte = $ten.attr('aria-expanded') === 'true';
		$('.przelacznik-inwestycji').each(function () {
			zamknijPodmenu($(this));
		});
		if (!otwarte) {
			$ten.attr({
				'aria-expanded': 'true',
				'aria-label': 'Zwiń listę inwestycji',
			});
			$('#' + $ten.attr('aria-controls')).addClass('otwarte');
		}
	});

	$(document).on('click', function (zdarzenie) {
		if (!$(zdarzenie.target).closest('.pozycja-inwestycje').length) {
			$('.przelacznik-inwestycji').each(function () {
				zamknijPodmenu($(this));
			});
		}
	});

	$(document).on('keydown', function (zdarzenie) {
		if (zdarzenie.key === 'Escape') {
			var $otwarte = $('.przelacznik-inwestycji[aria-expanded="true"]');
			$otwarte.each(function () {
				zamknijPodmenu($(this));
			});
			$otwarte.first().trigger('focus');
		}
	});

	// --- ulubione mieszkania ---
	var KLUCZ_ULUBIONYCH = 'ulubione-lokale';

	function uproscTekst(tekst) {
		return tekst.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
			.replace(/ł/g, 'l').replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
	}

	function pobierzUlubione() {
		try {
			var zapisane = JSON.parse(localStorage.getItem(KLUCZ_ULUBIONYCH) || '[]');
			return Array.isArray(zapisane) ? zapisane : [];
		} catch (blad) {
			return [];
		}
	}

	function zapiszUlubione(ulubione) {
		try {
			localStorage.setItem(KLUCZ_ULUBIONYCH, JSON.stringify(ulubione));
		} catch (blad) {
			// Brak dostępu do pamięci nie może blokować pozostałych funkcji strony.
		}
	}

	$('.grupa-wynikow').each(function () {
		var $grupa = $(this);
		var inwestycja = $grupa.find('.baner-grupy h2').text().trim();
		$grupa.find('.wiersz-lokalu').each(function (indeks) {
			var $wiersz = $(this);
			var lokal = $wiersz.find('.nazwa-lokalu').text().trim();
			var id = uproscTekst(inwestycja + '-' + lokal) + '-' + (indeks + 1);
			$wiersz.find('.do-ulubionych').attr('data-ulubione-id', id)
				.data('nazwa-ulubionego', lokal + ' – ' + inwestycja);
		});
	});

	$('.akcje-lokalu .ulubione').data('nazwa-ulubionego', 'Mieszkanie C.1 – Dom Hygge Twin');

	// --- filtrowanie i sortowanie wyników ---
	// Liczba z tekstu wiersza: "1.400.573,30 zł" -> 1400573.3, "67,9 m²" -> 67.9
	function liczbaZTekstu(tekst) {
		var czysty = tekst.replace(/\s/g, '').replace(/\./g, '').replace(',', '.');
		var dopasowanie = czysty.match(/\d+(\.\d+)?/);
		return dopasowanie ? parseFloat(dopasowanie[0]) : NaN;
	}

	function daneLokalu($wiersz) {
		var $mocne = $wiersz.find('.linia.mocna');
		var pietro = $wiersz.find('.pietro strong').text().trim().toLowerCase();
		return {
			powierzchnia: liczbaZTekstu($mocne.eq(0).text()),
			pokoje: liczbaZTekstu($mocne.eq(1).text()),
			pietro: pietro === 'parter' ? 0 : liczbaZTekstu(pietro),
			cena: liczbaZTekstu($wiersz.find('.cena').text()),
			status: $wiersz.find('.status').text().trim().toLowerCase()
		};
	}

	// Opcja z listy jako warunek: "do 40 m²", "40 - 60 m²", "powyżej 90 m²", "4 i więcej",
	// "Parter", "2". Pierwsza pozycja listy ("Dowolna") nie trafia tu wcale.
	function spelniaWarunek(opcja, wartosc) {
		var tekst = opcja.toLowerCase().replace(/(\d)\s+(?=\d)/g, '$1');
		var liczby = (tekst.match(/\d+(,\d+)?/g) || []).map(function (liczba) {
			return parseFloat(liczba.replace(',', '.'));
		});

		if (tekst === 'parter') {
			return wartosc === 0;
		}
		if (!liczby.length) {
			return true;
		}
		if (tekst.indexOf('do ') === 0) {
			return wartosc < liczby[0];
		}
		if (tekst.indexOf('powyżej') === 0) {
			return wartosc > liczby[0];
		}
		if (tekst.indexOf('więcej') !== -1 || tekst.indexOf('wyżej') !== -1) {
			return wartosc >= liczby[0];
		}
		if (liczby.length > 1) {
			return wartosc >= liczby[0] && wartosc <= liczby[1];
		}
		return wartosc === liczby[0];
	}

	function filtrujWyniki() {
		var $grupy = $('.grupa-wynikow');
		var $formularz = $('.filtry-wynikow');
		if (!$grupy.length || !$formularz.length) {
			return;
		}

		var warunki = [];
		$formularz.find('.pole-filtru').each(function () {
			var $wybrana = $(this).next('.lista-filtru').find('.dropdown-item.wybrany');
			if ($wybrana.length && $wybrana.closest('li').index() > 0) {
				warunki.push({ pole: $(this).data('pole'), opcja: $wybrana.text().trim() });
			}
		});

		// Nazwa ze strony głównej bywa krótsza niż nazwa grupy ("Lake Village")
		var inwestycja = ($formularz.find('input[name="inwestycja"]').val() || '').toLowerCase();
		var trybUlubionych = new URLSearchParams(window.location.search).get('ulubione') === '1';
		var ulubione = pobierzUlubione();
		var widoczneRazem = 0;

		$grupy.each(function () {
			var $grupa = $(this);
			var nazwaGrupy = $grupa.find('.baner-grupy h2').text().trim().toLowerCase();
			var pasujeInwestycja = !inwestycja || inwestycja === 'wszystkie inwestycje' ||
				nazwaGrupy.indexOf(inwestycja) !== -1;
			var widoczne = 0;

			$grupa.find('.wiersz-lokalu').each(function () {
				var $wiersz = $(this);
				var dane = daneLokalu($wiersz);
				var pokaz = pasujeInwestycja && warunki.every(function (warunek) {
					if (warunek.pole === 'status') {
						return dane.status === warunek.opcja.toLowerCase();
					}
					return spelniaWarunek(warunek.opcja, dane[warunek.pole]);
				});
				if (trybUlubionych) {
					pokaz = pokaz &&
						ulubione.indexOf($wiersz.find('.do-ulubionych').data('ulubione-id')) !== -1;
				}
				$wiersz.prop('hidden', !pokaz);
				if (pokaz) widoczne += 1;
			});

			$grupa.prop('hidden', widoczne === 0);
			widoczneRazem += widoczne;
		});

		var $brak = $('.brak-ulubionych');
		if (!$brak.length) {
			$brak = $('<p class="brak-ulubionych"></p>');
			$grupy.first().before($brak);
		}
		$brak.text(trybUlubionych && !ulubione.length
			? 'Nie masz jeszcze żadnych ulubionych mieszkań.'
			: 'Brak mieszkań spełniających wybrane kryteria.');
		$brak.prop('hidden', widoczneRazem > 0);
		przeliczWyniki();
	}

	// "Wybierz" przywraca kolejność z HTML-a
	$('.wiersz-lokalu').each(function (indeks) {
		$(this).data('kolejnosc', indeks);
	});

	function sortujWyniki(sposob) {
		var klucz = sposob.indexOf('Cena') === 0 ? 'cena'
			: sposob.indexOf('Powierzchnia') === 0 ? 'powierzchnia' : null;
		var kierunek = sposob.indexOf('malejąco') !== -1 ? -1 : 1;

		$('.grupa-wynikow .lista-lokali').each(function () {
			var $lista = $(this);
			var wiersze = $lista.children('.wiersz-lokalu').get();
			wiersze.sort(function (a, b) {
				if (!klucz) {
					return $(a).data('kolejnosc') - $(b).data('kolejnosc');
				}
				return (daneLokalu($(a))[klucz] - daneLokalu($(b))[klucz]) * kierunek;
			});
			$lista.append(wiersze);
		});
	}

	function ustawWidokUlubionych() {
		filtrujWyniki();
	}

	function ustawNaglowekUlubionych() {
		if (!$('.naglowek-wynikow').length ||
			new URLSearchParams(window.location.search).get('ulubione') !== '1') {
			return;
		}

		// Akcent na drugim słowie - ten sam podział, co w "Wyniki wyszukiwania".
		$('.naglowek-wynikow .tytul-sekcji')
			.html('Ulubione <span class="akcent">Inwestycje</span>');
		$('.naglowek-wynikow .okruszki span').not('.rozdzielacz').last()
			.text('Ulubione Inwestycje');
		document.title = 'Ulubione Inwestycje - Dynamic Development';
	}

	function przeliczWyniki() {
		var $liczba = $('.liczba-wynikow');
		if (!$liczba.length) {
			return;
		}
		$liczba.text($('.grupa-wynikow').not('[hidden]').find('.wiersz-lokalu')
			.not('[hidden]').length);
	}

	function aktualizujUlubione() {
		var ulubione = pobierzUlubione();
		$('.do-ulubionych, .akcje-lokalu .ulubione').each(function () {
			var $przycisk = $(this);
			var aktywny = ulubione.indexOf($przycisk.data('ulubione-id')) !== -1;
			var nazwa = $przycisk.data('nazwa-ulubionego') || 'mieszkanie';
			$przycisk.toggleClass('aktywne', aktywny).attr({
				'aria-pressed': aktywny ? 'true' : 'false',
				'aria-label': (aktywny ? 'Usuń ' : 'Dodaj ') + nazwa +
					(aktywny ? ' z ulubionych' : ' do ulubionych'),
			});
		});
		$('.licznik-ulubionych').text(ulubione.length).closest('a')
			.attr('href', 'wyszukiwarka.html?ulubione=1');
		ustawWidokUlubionych(ulubione);
		przeliczWyniki();
	}

	$(document).on('click', '.do-ulubionych, .akcje-lokalu .ulubione', function () {
		var id = $(this).data('ulubione-id');
		if (!id) return;
		var ulubione = pobierzUlubione();
		var pozycja = ulubione.indexOf(id);
		if (pozycja === -1) {
			ulubione.push(id);
		} else {
			ulubione.splice(pozycja, 1);
		}
		zapiszUlubione(ulubione);
		aktualizujUlubione();
	});

	$(window).on('storage', aktualizujUlubione);
	ustawNaglowekUlubionych();
	aktualizujUlubione();

	// Próg układu mobilnego (zachowanie nagłówka przy przewijaniu)
	var PROG_MOBILNY = 1024;
	var mediaMobilne = window.matchMedia('(max-width: ' + PROG_MOBILNY + 'px)');

	// Firefox nie oblicza dzielenia długości w calc()
	if (!window.CSS || !CSS.supports('zoom', 'calc(100vw / 1920px)')) {
		var PROG_PELNEGO_DESKTOPU = 1900;
		var SZEROKOSC_PROJEKTU = 1920;
		var ustawZoomPelnegoDesktopu = function () {
			var szerokosc = window.innerWidth;
			var aktywny = szerokosc >= PROG_PELNEGO_DESKTOPU && szerokosc < SZEROKOSC_PROJEKTU;
			document.documentElement.style.overflowX = aktywny ? 'hidden' : '';
			document.body.style.width = aktywny ? SZEROKOSC_PROJEKTU + 'px' : '';
			document.body.style.zoom = aktywny ? szerokosc / SZEROKOSC_PROJEKTU : '';
		};
		ustawZoomPelnegoDesktopu();
		window.addEventListener('resize', ustawZoomPelnegoDesktopu);
	}
	var PROG_MENU_KOMPAKTOWE = 1024;
	var mediaMenuKompaktowe = window.matchMedia(
		'(max-width: ' + PROG_MENU_KOMPAKTOWE + 'px)');

	// --- nagłówek wracający przy przewijaniu w górę ---
	var $naglowek = $('.naglowek');
	var ostatniaPozycja = Math.max(0, window.scrollY || window.pageYOffset);
	var naglowekZaplanowany = false;
	var TOLERANCJA_NAGLOWKA = 5;
	var PROG_NAGLOWKA = 150;

	function ustawNaglowekPrzyScrollu() {
		naglowekZaplanowany = false;
		var teraz = Math.max(0, window.scrollY || window.pageYOffset);

		if (!mediaMobilne.matches) {
			$naglowek.removeClass('przypiety odpiety');
			ostatniaPozycja = teraz;
			return;
		}

		if (document.documentElement.classList.contains('dosuwanie')) {
			ostatniaPozycja = teraz;
			return;
		}

		if (teraz <= PROG_NAGLOWKA) {
			$naglowek.addClass('przypiety').removeClass('odpiety');
			ostatniaPozycja = teraz;
			return;
		}

		var roznica = teraz - ostatniaPozycja;
		if (Math.abs(roznica) < TOLERANCJA_NAGLOWKA) {
			return;
		}

		$naglowek.toggleClass('odpiety', roznica > 0)
			.toggleClass('przypiety', roznica < 0);
		ostatniaPozycja = teraz;
	}

	$(window).on('scroll', function () {
		if (naglowekZaplanowany) {
			return;
		}
		naglowekZaplanowany = true;
		window.requestAnimationFrame(ustawNaglowekPrzyScrollu);
	});

	if (mediaMobilne.addEventListener) {
		mediaMobilne.addEventListener('change', ustawNaglowekPrzyScrollu);
	} else {
		mediaMobilne.addListener(ustawNaglowekPrzyScrollu);
	}
	ustawNaglowekPrzyScrollu();

	// --- boczne akcje w menu mobilnym ---
	var $pasekBoczny = $('.pasek-boczny').first();
	var miejscePaska = null;

	if ($pasekBoczny.length) {
		miejscePaska = document.createComment('miejsce paska bocznego');
		$pasekBoczny[0].parentNode.insertBefore(miejscePaska, $pasekBoczny[0]);
	}

	function ustawPasekBoczny() {
		if (!$pasekBoczny.length) return;
		if (mediaMenuKompaktowe.matches) {
			$('.menu-mobilne .offcanvas-body').append($pasekBoczny);
		} else {
			$(miejscePaska).after($pasekBoczny);
		}
	}

	ustawPasekBoczny();
	if (mediaMenuKompaktowe.addEventListener) {
		mediaMenuKompaktowe.addEventListener('change', ustawPasekBoczny);
	} else {
		mediaMenuKompaktowe.addListener(ustawPasekBoczny);
	}

	// --- przełącznik trybu jasny/ciemny ---
	var $przelacznik = $('#przelacznikMotywu');

	if (localStorage.getItem('motyw') === 'jasny') {
		$('html').addClass('motyw-jasny');
	}

	$przelacznik.on('click', function () {
		var jasny = $('html').toggleClass('motyw-jasny').hasClass('motyw-jasny');
		localStorage.setItem('motyw', jasny ? 'jasny' : 'ciemny');
		$(this).attr('aria-label', jasny ? 'Zmień tryb na ciemny' : 'Zmień tryb na jasny');
	});

	// --- stopka odsłaniana ---
	var $stopka = $('.stopka');
	var $tresc = $('main');

	var bezRuchu = window.matchMedia &&
		window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function aktualizujPozycjeStopki() {
		if (!$('body').hasClass('stopka-odslaniana') || !$stopka.length) {
			$stopka.css('transform', '');
			return;
		}

		var okno = window.innerHeight;
		var wysStopki = $stopka.outerHeight();

		// Gdy stopka jest wyższa od okna
		if (wysStopki > okno) {
			var scrollMax = Math.max(0, document.documentElement.scrollHeight - okno);
			var terazY = window.pageYOffset || document.documentElement.scrollTop || 0;
			var ileDoDolu = scrollMax - terazY;
			var nadmiar = wysStopki - okno;
			var przesuniecie = Math.max(0, Math.min(nadmiar, ileDoDolu));
			$stopka[0].style.transform = 'translate3d(0, ' + przesuniecie + 'px, 0)';
		} else {
			$stopka[0].style.transform = '';
		}
	}

	function ustawStopke() {
		if (!$stopka.length || !$tresc.length) {
			return;
		}

		var okno = window.innerHeight;

		// Efekt odsłaniania ma działać na każdej stronie i w każdej rozdzielczości, o ile treść
		// strony jest dłuższa niż wysokość okna.
		var wartoOdslaniac = !bezRuchu && $tresc.outerHeight() > okno;

		$('body').toggleClass('stopka-odslaniana', wartoOdslaniac);
		$tresc.css('margin-bottom', wartoOdslaniac ? $stopka.outerHeight() : '');
		aktualizujPozycjeStopki();
	}

	ustawStopke();
	$(window).on('load', ustawStopke);

	var stopkaZaplanowana = false;
	function obsluzScrollStopki() {
		stopkaZaplanowana = false;
		aktualizujPozycjeStopki();
	}

	$(window).on('scroll', function () {
		if (!stopkaZaplanowana) {
			stopkaZaplanowana = true;
			window.requestAnimationFrame(obsluzScrollStopki);
		}
	});

	var licznikZmiany;
	$(window).on('resize', function () {
		clearTimeout(licznikZmiany);
		licznikZmiany = setTimeout(ustawStopke, 150);
	});

	if (window.ResizeObserver && $stopka.length) {
		new ResizeObserver(ustawStopke).observe($stopka[0]);
	}

	// --- inwestycja.html: przełącznik widoku listy mieszkań (kafelki/lista) ---
	$(document).on('click', '.przelacznik-widoku', function () {
		var $przycisk = $(this);

		$przycisk.siblings('.przelacznik-widoku').addBack()
			.removeClass('wybrany').attr('aria-pressed', 'false');
		$przycisk.addClass('wybrany').attr('aria-pressed', 'true');

		$('.kontener-mieszkan').toggleClass('widok-listy', $przycisk.data('widok') === 'lista');
	});

	// --- inwestycja.html: zdjęcie nagłówka przy 1025-1399px ---
	// Im węższe okno, tym niżej zdjęcie: przy 1399px stoi tam, gdzie ustawia je CSS, a przy
	// 1025px na środku wysokości kolumny tekstu. Przy samej górze pasma pigułki stoją obok
	// siebie i sięgają pod kadr, więc zdjęcie nie może tam zejść na wysokość kolumny.
	// Wysokość kolumny zależy od łamania tekstu, więc bierzemy ją z układu.
	var zdjecieNaglowka = document.querySelector('.inwestycja-hero > .inwestycja-zdjecie');
	var kolumnaNaglowka = document.querySelector('.inwestycja-hero-kolumna');
	var pasmoZdjecia = window.matchMedia('(min-width: 1025px) and (max-width: 1399.98px)');

	// offsetTop, a nie getBoundingClientRect - animacja wejścia przesuwa oba bloki transformem
	function odGoryStrony(el) {
		var y = 0;
		for (; el; el = el.offsetParent) {
			y += el.offsetTop;
		}
		return y;
	}

	function ustawZdjecieNaglowka() {
		if (!zdjecieNaglowka || !kolumnaNaglowka) {
			return;
		}

		zdjecieNaglowka.style.removeProperty('top');
		if (!pasmoZdjecia.matches) {
			return;
		}

		var postep = Math.min(1, Math.max(0, (1400 - window.innerWidth) / (1400 - 1025)));
		var srodek = odGoryStrony(kolumnaNaglowka) +
			(kolumnaNaglowka.offsetHeight - zdjecieNaglowka.offsetHeight) / 2;
		var zejscie = Math.max(0, srodek - odGoryStrony(zdjecieNaglowka));
		var top = parseFloat(window.getComputedStyle(zdjecieNaglowka).top) || 0;

		zdjecieNaglowka.style.top = (top + postep * zejscie) + 'px';
	}

	if (zdjecieNaglowka) {
		ustawZdjecieNaglowka();
		$(window).on('load resize', ustawZdjecieNaglowka);
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(ustawZdjecieNaglowka);
		}
	}

})(jQuery);
