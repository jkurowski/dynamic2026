/* Slider inwestycji na hero.
   W makiecie licznik pokazuje "1 / 4", ale w Figmie jest tylko jeden slajd z treścią
   (Dom Hygge Twin) - pozostałe trzy nie zostały zaprojektowane. Dlatego slajdy trzymamy
   w tablicy poniżej: pierwszy jest też wpisany na sztywno w HTML (dla SEO), resztę
   dopisze backend przy generowaniu strony. */

(function ($) {
	'use strict';

	var slajdy = [
		{
			lokalizacja: 'WARSZAWA · MOKOTÓW',
			tytul: 'Dom Hygge Twin',
			link: '/inwestycje/dom-hygge-twin',
			zdjecieWebp: '/img/hero-1920.webp',
			zdjecieJpg: '/img/hero-1920.jpg'
		},
		{
			lokalizacja: 'CHYLICE · KONSTANCIN-JEZIORNA',
			tytul: 'Konstancin Riverside House',
			link: '/inwestycje/konstancin-riverside-house',
			zdjecieWebp: '/img/hero-konstancin-hd.webp',
			zdjecieJpg: '/img/hero-konstancin-hd.jpg'
		},
		{
			lokalizacja: 'ZALESIE GÓRNE · GM. PIASECZNO',
			tytul: 'Segmenty Lake Village',
			link: '/inwestycje/lake-village',
			zdjecieWebp: '/img/hero-lake-hd.webp',
			zdjecieJpg: '/img/hero-lake-hd.jpg'
		},
		{
			lokalizacja: 'NOWA WOLA · GM. LESZNOWOLA',
			tytul: 'Zespół willowy Zielona Polana',
			link: '/inwestycje/zielona-polana',
			zdjecieWebp: '/img/poznaj-onas.webp',
			zdjecieJpg: '/img/poznaj-onas.jpg'
		}
	];

	// Wstępne załadowanie grafik slajdów w tle, aby kliknięcie w przełącznik slajdu zawsze
	// reagowało natychmiast, bez czekania na sieć.
	slajdy.forEach(function (s) {
		var w = new Image();
		w.src = s.zdjecieWebp;
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

		// Wyzwolenie płynnego pojawienia się nowego tekstu
		$tresc.addClass('slajd-przelacza');

		zegarTekstu = setTimeout(function () {
			$tresc.removeClass('slajd-przelacza');
		}, CZAS_PRZENIKANIA);
	}

	function zmienZdjecie(slajd, numer) {
		// zdjęcie siedzi w <picture>, więc trzeba podmienić oba źródła, nie samo <img>
		var $nowe = $wzorZdjecia.clone().addClass('zdjecie-wchodzi');
		$nowe.find('source[type="image/webp"]').attr('srcset', slajd.zdjecieWebp);
		$nowe.find('source[type="image/jpeg"]').attr('srcset', slajd.zdjecieJpg);
		$nowe.find('img').attr({
			src: slajd.zdjecieJpg,
			alt: 'Wizualizacja inwestycji ' + slajd.tytul
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
