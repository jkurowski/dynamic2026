/* Slider inwestycji na hero.
   Slajdy przychodzą z CMS (panel -> Slider) w window.slajdyHero - wypisuje je widok strony głównej
   (Slider::slajdyHero(); gdy w panelu nie ma aktywnych slajdów, są to slajdy z makiety).
   Pierwszy slajd jest też wyrenderowany w HTML (dla SEO).
   Pola slajdu: lokalizacja, tytul, link, przycisk, cel, alt, zdjecieJpg, srcsetJpg, srcsetWebp. */
(function ($) {
	'use strict';

	var slajdy = window.slajdyHero || [];

	// Wstępne załadowanie grafik slajdów w tle, aby kliknięcie w przełącznik slajdu zawsze
	// reagowało natychmiast, bez czekania na sieć.
	// (przeglądarka sama wybierze rozmiar z srcset - tu tylko główny wariant 1920)
	slajdy.forEach(function (s) {
		var j = new Image();
		j.src = s.zdjecieJpg;
	});

	var CZAS_ZNIKANIA = 140;
	var CZAS_POWROTU = 300;
	var CZAS_PRZENIKANIA = 600;

	var aktualny = 0;

	var $hero = $('.hero');
	if (!$hero.length) {
		return;
	}

	var $tlo = $hero.find('.hero-tlo');
	var $tresc = $hero.find('.hero-tresc');
	var $lokalizacja = $hero.find('.tekst-lokalizacji');
	var $tytul = $hero.find('.hero-naglowek');
	var $przycisk = $hero.find('.hero-przycisk');

	if (!slajdy.length) {
		return;
	}

	$hero.find('.ile-slajdow').text(slajdy.length);

	// przy jednym slajdzie nie ma czego przewijać
	if (slajdy.length < 2) {
		$hero.find('.slajd-poprzedni, .slajd-nastepny').prop('disabled', true).css('opacity', .45);
		return;
	}

	// Wzorzec warstwy zdjęcia
	var $wzorZdjecia = $tlo.children('picture').first().clone();
	$wzorZdjecia.find('img').removeAttr('fetchpriority');

	// Numer przejścia
	var przejscie = 0;
	var zegarTekstu = null;

	function zmienTekst(slajd) {
		clearTimeout(zegarTekstu);

		// Zdejmujemy klasę animacji i wymuszamy reflow, by natychmiast uruchomić subtelną zmianę
		// tekstu
		$tresc.removeClass('slajd-przelacza');
		void $tresc[0].offsetWidth;

		// Podmiana treści od razu w chwili kliknięcia
		$lokalizacja.text(slajd.lokalizacja);
		$tytul.text(slajd.tytul);
		$przycisk.attr('href', slajd.link);
		if (slajd.cel) {
			$przycisk.attr('target', slajd.cel);
		} else {
			$przycisk.removeAttr('target');
		}
		// napis przycisku to pierwszy węzeł tekstowy przed ikoną strzałki
		$przycisk.contents().filter(function () {
			return this.nodeType === 3 && this.nodeValue.trim() !== '';
		}).first().replaceWith(document.createTextNode(' ' + slajd.przycisk + ' '));

		// Wyzwolenie płynnego pojawienia się nowego tekstu
		$tresc.addClass('slajd-przelacza');

		zegarTekstu = setTimeout(function () {
			$tresc.removeClass('slajd-przelacza');
		}, CZAS_PRZENIKANIA);
	}

	function zmienZdjecie(slajd, numer) {
		// zdjęcie siedzi w <picture>, więc trzeba podmienić oba źródła, nie samo <img>
		var $nowe = $wzorZdjecia.clone().addClass('zdjecie-wchodzi');
		$nowe.find('source[type="image/webp"]').attr('srcset', slajd.srcsetWebp);
		$nowe.find('source[type="image/jpeg"]').attr('srcset', slajd.srcsetJpg);
		$nowe.find('img').attr({
			src: slajd.zdjecieJpg,
			alt: slajd.alt
		});

		var $stare = $tlo.children('picture');

		function odslon() {
			if (numer !== przejscie) {
				$nowe.remove();
				return;
			}

			// Wymuszone przeliczenie układu. Bez niego przeglądarka ma szansę zobaczyć wyłącznie
			// stan końcowy warstwy i przejście w ogóle nie ruszy.
			void $nowe[0].offsetWidth;
			$nowe.addClass('zdjecie-widoczne');
			$stare.addClass('zdjecie-znika');

			setTimeout(function () {
				if (numer !== przejscie) {
					return;
				}
				$stare.remove();
				$nowe.removeClass('zdjecie-wchodzi zdjecie-widoczne');
			}, CZAS_PRZENIKANIA);
		}

		$nowe.appendTo($tlo);

		var obraz = $nowe.find('img')[0];
		if (obraz.complete) {
			odslon();
		} else {
			$(obraz).one('load error', odslon);
		}
	}

	function pokaz(indeks) {
		var slajd = slajdy[indeks];

		przejscie += 1;

		// Tekst rusza od razu po kliknięciu, a zdjęcie dołącza do przenikania.
		zmienTekst(slajd);
		zmienZdjecie(slajd, przejscie);

		$hero.find('.numer-slajdu').text(indeks + 1);
	}

	$hero.on('click', '.slajd-nastepny', function () {
		aktualny = (aktualny + 1) % slajdy.length;
		pokaz(aktualny);
	});

	$hero.on('click', '.slajd-poprzedni', function () {
		aktualny = (aktualny - 1 + slajdy.length) % slajdy.length;
		pokaz(aktualny);
	});

})(jQuery);
