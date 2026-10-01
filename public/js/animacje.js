/* Pojawianie się elementów przy przewijaniu.

   Elementy oznaczone w HTML klasą "pojawia-sie" (wjazd z dołu, z modyfikatorem
   "z-lewej" albo "z-prawej" - wysunięcie z krawędzi ekranu), "rozjasnia-sie"
   (zmiana koloru nagłówka) albo "kolejno" (lista wchodzi pozycjami) dostają klasę
   "widoczny", gdy wjadą w kadr. Klasa zostaje na stałe, a element wypisujemy
   z obserwatora - animacja ma zagrać raz i tyle.

   Do wykrywania używamy IntersectionObserver zamiast nasłuchu na scroll: przeglądarka
   liczy to sama, poza głównym wątkiem, więc przewijanie zostaje płynne.

   Trzy rzeczy, o których trzeba pamiętać przy dokładaniu kolejnych elementów:
   1. Nie dawaj jednemu elementowi obu klas naraz. "pojawia-sie" ustawia przejście
      dla opacity i transform, "rozjasnia-sie" dla koloru - druga deklaracja
      nadpisałaby pierwszą i animacja przestałaby działać.
   2. Nie oznaczaj elementu, który gdzieś jest ukryty przez display: none.
      Taki element nigdy nie przetnie kadru, więc nie dostanie klasy "widoczny"
      i po pokazaniu go zostałby na zawsze przezroczysty.
   3. Element z display: contents też nie ma własnego pudełka - obsługujemy to
      niżej, obserwując w jego miejsce pierwsze dziecko, które pudełko ma.
      Temu dziecku nie dawaj już własnej klasy wejścia: obserwator prowadzi
      jeden wpis na obserwowany węzeł, więc jedno z dwóch oznaczeń przepadnie. */

(function ($) {
	'use strict';

	var $elementy = $('.pojawia-sie, .rozjasnia-sie, .kolejno');
	if (!$elementy.length) {
		return;
	}

	// Starsza przeglądarka albo systemowe ograniczenie animacji - pokazujemy wszystko od
	// razu i kończymy. Strona ma działać tak samo, tylko bez efektu.
	var bezRuchu = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (bezRuchu || !('IntersectionObserver' in window)) {
		$elementy.addClass('widoczny');
		return;
	}

	// Element z display
	var oznaczany = new WeakMap();

	function celObserwacji(el) {
		if (window.getComputedStyle(el).display !== 'contents') {
			return el;
		}
		for (var i = 0; i < el.children.length; i++) {
			if (el.children[i].getClientRects().length) {
				return el.children[i];
			}
		}
		return el;
	}

	var obserwator = new IntersectionObserver(function (wpisy) {
		wpisy.forEach(function (wpis) {
			if (!wpis.isIntersecting) {
				return;
			}
			(oznaczany.get(wpis.target) || wpis.target).classList.add('widoczny');
			obserwator.unobserve(wpis.target);
		});
	}, {
		// element uznajemy za widoczny, gdy wjedzie 12% ponad dolną krawędź ekranu - dzięki temu
		// animacja startuje, zanim użytkownik na niego spojrzy
		rootMargin: '0px 0px -12% 0px',
		threshold: 0
	});

	$elementy.each(function () {
		var cel = celObserwacji(this);
		if (cel !== this) {
			oznaczany.set(cel, this);
		}
		obserwator.observe(cel);
	});

})(jQuery);


// Liczby odliczają od zera po wejściu sekcji w kadr.
(function ($) {
	'use strict';

	var $liczby = $('.duza-liczba');
	if (!$liczby.length) {
		return;
	}

	var CZAS = 1400;

	var bezRuchu = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (bezRuchu || !('IntersectionObserver' in window)) {
		return;
	}

	function odliczaj(el) {
		var docelowa = el.textContent.trim();
		var dopasowanie = docelowa.match(/^(\d+)(.*)$/);
		if (!dopasowanie) {
			return;
		}

		var cel = parseInt(dopasowanie[1], 10);
		var koncowka = dopasowanie[2];

		el.style.minWidth = el.getBoundingClientRect().width + 'px';

		var start = null;
		function klatka(czas) {
			if (start === null) {
				start = czas;
			}
			var postep = Math.min((czas - start) / CZAS, 1);
			var wygladzony = 1 - Math.pow(1 - postep, 3);
			el.textContent = Math.round(cel * wygladzony) + koncowka;

			if (postep < 1) {
				requestAnimationFrame(klatka);
				return;
			}

			el.style.minWidth = '';
		}
		requestAnimationFrame(klatka);
	}

	var obserwator = new IntersectionObserver(function (wpisy) {
		wpisy.forEach(function (wpis) {
			if (!wpis.isIntersecting) {
				return;
			}
			obserwator.unobserve(wpis.target);
			odliczaj(wpis.target);
		});
	}, { rootMargin: '0px 0px -12% 0px', threshold: 0 });

	$liczby.each(function () {
		obserwator.observe(this);
	});

})(jQuery);

// Duże zdjęcie sekcji jako tło strony
(function ($) {
	'use strict';

	var UKLADY = [
		{ sekcja: '.dlaczego-warto', zdjecie: '.dlaczego-zdjecie', tresc: '.dlaczego-tresc' },
		{ sekcja: '.fin-hero', zdjecie: '.fin-hero-zdjecie', tresc: '.fin-hero-tresc' },
		{ sekcja: '.inwestycja-hero', zdjecie: '.inwestycja-zdjecie', tresc: '.fin-hero-tresc' },
		{ sekcja: '.partner-wykonczenia', zdjecie: '.partner-zdjecie', tresc: ':scope > .tresc' },
		// Sekcja nagród (poznaj-nas) oraz Mokotowa (inwestycja) - kadr ze skosem trapezowym
		// schodzący nad treść poniżej 1025px.
		{ sekcja: '.nagrody', zdjecie: '.nagrody-zdjecie', tresc: ':scope > .tresc' }
	];

	var bezRuchu = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (bezRuchu) {
		return;
	}

	// Zapas wysokości ponad okno, w którym tło może powoli jechać do góry, oraz ułamek
	// prędkości strony, z jaką to robi.
	var ZAPAS = .12;
	var TEMPO = .5;

	// Efekt rusza dopiero, gdy dolna krawędź kadru wjedzie w okno
	function policzDroge(m, okno) {
		if (window.innerWidth <= 1024) {
			return Math.min(okno * .8, Math.max(okno * .6, m.height * 1.5));
		}
		return Math.min(okno * .45, m.height * .7);
	}

	function ileZjechano() {
		return window.pageYOffset || document.documentElement.scrollTop || 0;
	}

	// Jedna sztuka efektu
	function zrobTlo(sekcja, miejsce, kadr, tresc) {
		var czynne = false;
		var aktywne = false;
		var poWejsciu = false;

		function sprawdzPasmo() {
			czynne = getComputedStyle(sekcja).getPropertyValue('--parallaks').trim() === '1';
		}

		// Warstwa position
		function wjazdSkonczony() {
			if (poWejsciu) {
				return true;
			}
			if (!miejsce.classList.contains('widoczny')) {
				return false;
			}
			var t = getComputedStyle(miejsce).transform;
			poWejsciu = (t === 'none' || t === 'matrix(1, 0, 0, 1, 0, 0)');
			return poWejsciu;
		}

		function ustawOdstep() {
			if (!tresc) {
				return;
			}
			tresc.style.removeProperty('margin-top');
			if (!czynne) {
				return;
			}
			var okno = window.innerHeight;
			var m = miejsce.getBoundingClientRect();
			var droga = policzDroge(m, okno);
			var przerwa = tresc.getBoundingClientRect().top - m.bottom;
			var brakuje = droga - przerwa;
			if (brakuje > 0) {
				tresc.style.marginTop = brakuje + 'px';
			}
		}

		function zwolnij() {
			if (!aktywne) {
				return;
			}
			aktywne = false;
			sekcja.classList.remove('tlo-strony');
			sekcja.style.removeProperty('--postep');
			kadr.style.cssText = '';
		}

		function klatka() {
			// Animacja wejścia trzyma na bloku transform, a to zrobiłoby z niego układ odniesienia
			// dla warstwy position: fixed. Startujemy dopiero po niej.
			if (!czynne) {
				zwolnij();
				return false;
			}
			if (!wjazdSkonczony()) {
				zwolnij();
				return miejsce.classList.contains('widoczny');
			}

			var okno = window.innerHeight;
			var m = miejsce.getBoundingClientRect();
			var s = sekcja.getBoundingClientRect();

			if (s.bottom <= 0 || s.top >= okno) {
				zwolnij();
				return;
			}

			// Granatowy panel kolumny z treścią jedzie po tle od dołu
			if (tresc && tresc.getBoundingClientRect().top <= 0) {
				zwolnij();
				return;
			}

			var droga = policzDroge(m, okno);

			var przebyte = Math.min(okno - m.bottom, ileZjechano());
			var postep = Math.max(0, Math.min(1, przebyte / droga));

			if (postep <= 0) {
				zwolnij();
				return;
			}

			var szerokoscStrony = document.documentElement.clientWidth;

			var ROZLEW = .6;
			var rozlew = Math.min(postep / ROZLEW, 1);

			var zapas = okno * ZAPAS;
			var lewa = m.left * (1 - rozlew);
			var gora = m.top * (1 - rozlew);
			var szerokosc = m.width + (szerokoscStrony - m.width) * rozlew;
			var wysokosc = m.height + (okno + zapas - m.height) * rozlew;

			// Powolny dryf
			gora -= Math.max(0, przebyte - droga) * TEMPO;

			var doKonca = s.bottom - gora - 1 / (window.devicePixelRatio || 1);
			if (doKonca < wysokosc) {
				wysokosc = doKonca;
			}
			if (wysokosc <= 0) {
				zwolnij();
				return;
			}

			if (!aktywne) {
				aktywne = true;
				sekcja.classList.add('tlo-strony');
			}

			sekcja.style.setProperty('--postep', postep.toFixed(4));
			kadr.style.left = lewa + 'px';
			kadr.style.top = gora + 'px';
			kadr.style.width = szerokosc + 'px';
			kadr.style.height = wysokosc + 'px';
		}

		sprawdzPasmo();
		ustawOdstep();

		return {
			klatka: klatka,
			przemierz: function () {
				zwolnij();
				sprawdzPasmo();
				ustawOdstep();
			}
		};
	}

	var tla = [];
	UKLADY.forEach(function (uklad) {
		Array.prototype.forEach.call(document.querySelectorAll(uklad.sekcja), function (sekcja) {
			var miejsce = sekcja.querySelector(uklad.zdjecie);
			var kadr = miejsce && miejsce.querySelector('picture');
			if (!kadr) {
				return;
			}
			tla.push(zrobTlo(sekcja, miejsce, kadr, sekcja.querySelector(uklad.tresc)));
		});
	});

	if (!tla.length) {
		return;
	}

	var czeka = false;

	function klatka() {
		czeka = false;
		var jeszcze = false;
		tla.forEach(function (tlo) {
			if (tlo.klatka()) {
				jeszcze = true;
			}
		});
		// Któraś sekcja czeka na koniec swojej animacji wejścia
		if (jeszcze) {
			zaplanuj();
		}
	}

	function zaplanuj() {
		if (czeka) {
			return;
		}
		czeka = true;
		requestAnimationFrame(klatka);
	}

	$(window).on('scroll', zaplanuj);
	$(window).on('resize', function () {
		tla.forEach(function (tlo) {
			tlo.przemierz();
		});
		zaplanuj();
	});

	zaplanuj();

})(jQuery);

// Karty sekcji na ekranach dotykowych (≤ 1024px)
(function ($) {
	'use strict';

	var bezRuchu = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var tresc = document.querySelector('main#tresc');
	var karty = tresc ? tresc.querySelectorAll(':scope > .sekcja-karta') : [];
	if (bezRuchu || !karty.length) {
		return;
	}

	var MAKS_SZEROKOSC = 1024;
	// Karta dojeżdża, gdy widać z niej choćby tyle (ułamek wysokości karty, a przy karcie
	// wyższej od ekranu - ułamek okna).
	var PROG_WEJSCIA = .1;
	// Przy przewijaniu w górę karta zostaje, gdy widać jej ponad tyle; mniej - wyjeżdża.
	var PROG_POWROTU = .6;
	// Zapasowe wykrywanie końca przewijania dla przeglądarek bez zdarzenia scrollend.
	var CZAS_BEZCZYNNOSCI = 340;
	// Najdłuższy dojazd, jaki wolno wykonać bez udziału palca (ułamek wysokości okna)
	var MAKS_DOSUNIECIE = .35;

	function ileZjechano() {
		return window.pageYOffset || document.documentElement.scrollTop || 0;
	}

	function naDotyku() {
		return window.innerWidth <= MAKS_SZEROKOSC;
	}

	// --- przypinanie ---
	var sekcje = [];
	var ostatniaSzerokosc = 0;
	var ostatniaWysokosc = 0;

	function zRozlewem(el) {
		return getComputedStyle(el).getPropertyValue('--parallaks').trim() === '1';
	}

	// Pasek, który sam z siebie prosi, żeby go nie przypinać
	function bezPrzypiecia(el) {
		return getComputedStyle(el).getPropertyValue('--przypinanie').trim() === '0';
	}

	function wyczysc() {
		sekcje.forEach(function (el) {
			el.style.removeProperty('position');
			el.style.removeProperty('top');
			el.style.removeProperty('z-index');
		});
	}

	function ustawPrzypiecia() {
		ostatniaSzerokosc = window.innerWidth;
		ostatniaWysokosc = window.innerHeight;
		wyczysc();
		sekcje = Array.prototype.filter.call(tresc.children, function (el) {
			return el.tagName !== 'SCRIPT' && el.tagName !== 'TEMPLATE';
		});
		if (!naDotyku()) {
			return;
		}

		var zjechano = ileZjechano();
		var gory = sekcje.map(function (el) {
			return el.getBoundingClientRect().top + zjechano;
		});
		var wysokosci = sekcje.map(function (el) {
			return el.offsetHeight;
		});
		var okno = window.innerHeight;

		// Rosnąca kolejność warstw
		sekcje.forEach(function (el, i) {
			el.style.zIndex = String(i + 1);
		});

		sekcje.forEach(function (karta, k) {
			if (!karta.classList.contains('sekcja-karta')) {
				return;
			}
			var zajete = 0;
			for (var i = k - 1; i >= 0 && zajete < okno; i--) {
				var el = sekcje[i];
				if (zRozlewem(el) || !wysokosci[i]) {
					break;
				}
				if (bezPrzypiecia(el)) {
					continue;
				}
				el.style.position = 'sticky';
				// Każda sekcja wyżej staje piksel niżej niż wynika z układu i chowa dolny brzeg pod
				// następną. Tak dwa osobno zaokrąglone przypięcia nie zostawiają między sobą szczeliny
				// (do 0,5px), przez którą prześwitywały pomarańczowe elementy sekcji przypiętych
				// wcześniej.
				el.style.top = (Math.round(okno - (gory[k] - gory[i])) + (k - 1 - i)) + 'px';
				zajete += wysokosci[i];
			}
		});
	}

	var czekaPrzeliczenie = false;
	function zaplanujPrzeliczenie() {
		if (czekaPrzeliczenie) {
			return;
		}
		czekaPrzeliczenie = true;
		requestAnimationFrame(function () {
			czekaPrzeliczenie = false;
			ustawPrzypiecia();
		});
	}

	ustawPrzypiecia();
	$(window).on('load', zaplanujPrzeliczenie);
	$(window).on('resize', function () {
		if (window.innerWidth !== ostatniaSzerokosc || Math.abs(window.innerHeight - ostatniaWysokosc) > 150) {
			zaplanujPrzeliczenie();
		}
	});
	// Sekcje zmieniają wysokość same z siebie
	if (window.ResizeObserver) {
		var obserwatorWysokosci = new ResizeObserver(zaplanujPrzeliczenie);
		sekcje.forEach(function (el) {
			obserwatorWysokosci.observe(el);
		});
	}

	// --- dosuwanie ---
	var animacjaId = null;
	var czyAnimuje = false;
	var czyDotyka = false;
	var licznik = null;
	var ostatniScroll = ileZjechano();
	var kierunek = 0;
	var koniecAnimacji = 0;

	// Krzywa rusza od razu ze średnią prędkością dojazdu i hamuje do zera na końcu
	function krzywa(t) {
		return t + t * t * (1 - t);
	}

	function zatrzymajAnimacje() {
		if (animacjaId) {
			cancelAnimationFrame(animacjaId);
			animacjaId = null;
		}
		czyAnimuje = false;
		document.documentElement.classList.remove('dosuwanie');
	}

	function jedzDo(celY) {
		zatrzymajAnimacje();
		var startY = ileZjechano();
		var droga = celY - startY;
		var odleglosc = Math.abs(droga);
		if (odleglosc < 2) {
			return;
		}
		var granica = window.innerHeight * MAKS_DOSUNIECIE;
		var czas;
		if (odleglosc <= granica) {
			czas = Math.max(320, Math.min(1400, 260 + odleglosc * .8));
		} else {
			var czasGranicy = 260 + granica * .8;
			czas = odleglosc * czasGranicy / granica;
		}
		var start = null;
		var ustawione = startY;
		czyAnimuje = true;
		// nagłówek (js/glowny.js) nie reaguje na ruch, którego nie zrobił użytkownik
		document.documentElement.classList.add('dosuwanie');

		function krok(teraz) {
			if (!czyAnimuje) {
				return;
			}
			if (Math.abs(ileZjechano() - ustawione) > 3) {
				zatrzymajAnimacje();
				ostatniScroll = ileZjechano();
				return;
			}
			if (start === null) {
				start = teraz;
			}
			var postep = Math.min(1, (teraz - start) / czas);
			// behavior: 'instant' - globalne scroll-behavior: smooth zamieniłoby każdą klatkę w
			// osobną, własną animację przeglądarki
			window.scrollTo({ top: startY + droga * krzywa(postep), behavior: 'instant' });
			ustawione = ileZjechano();
			if (postep < 1) {
				animacjaId = requestAnimationFrame(krok);
			} else {
				animacjaId = null;
				zatrzymajAnimacje();
				koniecAnimacji = performance.now();
				ostatniScroll = ileZjechano();
			}
		}
		animacjaId = requestAnimationFrame(krok);
	}

	// Stany, w których strona nie może sama się ruszyć
	function zajety() {
		if (fokusOdslonil) {
			return true;
		}
		var aktywny = document.activeElement;
		if (aktywny && aktywny.matches && aktywny.matches('input, textarea, select, [contenteditable="true"]')) {
			return true;
		}
		return !!document.querySelector('.offcanvas.show, .offcanvas.showing, .modal.show, body.modal-open');
	}

	// Wysokość nagłówka, jeśli akurat jest pokazany. Karta staje wtedy pod nim, a nie pod
	// spodem - inaczej nagłówek zasłaniałby jej początek.
	function pasekNaGorze() {
		var naglowek = document.querySelector('.naglowek.przypiety');
		if (!naglowek) {
			return 0;
		}
		return Math.max(0, naglowek.getBoundingClientRect().bottom);
	}

	function sprawdzDosuniecie() {
		if (!naDotyku() || czyDotyka || czyAnimuje || zajety()) {
			return;
		}
		// scrollend po naszym własnym dojeździe - nic więcej do zrobienia
		if (performance.now() - koniecAnimacji < 250) {
			return;
		}
		var okno = window.innerHeight;
		var zjechano = ileZjechano();
		var cel = null;

		for (var i = 0; i < karty.length; i++) {
			var r = karty[i].getBoundingClientRect();
			if (r.top <= 4 || r.top >= okno - 1) {
				continue;
			}
			var widac = (okno - r.top) / Math.min(r.height, okno);
			if (kierunek >= 0) {
				if (widac >= PROG_WEJSCIA) {
					cel = zjechano + r.top - pasekNaGorze();
				}
			} else if (widac >= PROG_POWROTU) {
				cel = zjechano + r.top - pasekNaGorze();
			} else {
				cel = zjechano - (okno - r.top);
			}
			break;
		}

		if (cel !== null) {
			var maks = Math.max(0, document.documentElement.scrollHeight - okno);
			jedzDo(Math.min(maks, Math.max(0, cel)));
		}
	}

	function zaplanujSprawdzenie(opoznienie) {
		clearTimeout(licznik);
		licznik = setTimeout(sprawdzDosuniecie, opoznienie);
	}

	var maScrollend = 'onscrollend' in window;

	window.addEventListener('scroll', function () {
		if (czyAnimuje) {
			return;
		}
		var teraz = ileZjechano();
		if (teraz !== ostatniScroll) {
			kierunek = teraz > ostatniScroll ? 1 : -1;
			ostatniScroll = teraz;
		}
		if (!czyDotyka) {
			// przy scrollend to tylko siatka bezpieczeństwa, stąd dłuższa zwłoka
			zaplanujSprawdzenie(maScrollend ? CZAS_BEZCZYNNOSCI * 2 : CZAS_BEZCZYNNOSCI);
		}
	}, { passive: true });

	// scrollend przychodzi dopiero, gdy wybrzmi rozpęd po puszczeniu palca - to właściwa chwila
	if (maScrollend) {
		window.addEventListener('scrollend', function () {
			if (!czyAnimuje && !czyDotyka) {
				zaplanujSprawdzenie(60);
			}
		});
	}

	window.addEventListener('touchstart', function () {
		czyDotyka = true;
		zatrzymajAnimacje();
		clearTimeout(licznik);
	}, { passive: true });

	window.addEventListener('touchend', function () {
		czyDotyka = false;
		zaplanujSprawdzenie(CZAS_BEZCZYNNOSCI);
	}, { passive: true });

	window.addEventListener('touchcancel', function () {
		czyDotyka = false;
	}, { passive: true });

	['wheel', 'mousedown', 'keydown'].forEach(function (typ) {
		window.addEventListener(typ, zatrzymajAnimacje, { passive: true });
	});

	// Fokus nie może wylądować pod kartą
	var fokusOdslonil = false;
	document.addEventListener('focusin', function (e) {
		if (!naDotyku()) {
			return;
		}
		var pole = e.target;
		requestAnimationFrame(function () {
			var r = pole.getBoundingClientRect();
			for (var i = 0; i < karty.length; i++) {
				var k = karty[i];
				if (k.contains(pole) || !(pole.compareDocumentPosition(k) & Node.DOCUMENT_POSITION_FOLLOWING)) {
					continue;
				}
				var kr = k.getBoundingClientRect();
				if (kr.top < r.bottom && kr.bottom > r.top) {
					zatrzymajAnimacje();
					fokusOdslonil = true;
					window.scrollTo({ top: ileZjechano() - (r.bottom - kr.top) - 24, behavior: 'instant' });
					return;
				}
			}
		});
	});
	// Po takim odsłonięciu dosuwanie nie może od razu zasunąć karty z powrotem. Wraca przy
	// pierwszym ruchu palcem albo kółkiem.
	['touchstart', 'wheel'].forEach(function (typ) {
		window.addEventListener(typ, function () {
			fokusOdslonil = false;
		}, { passive: true });
	});

})(jQuery);
