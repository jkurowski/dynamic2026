/* Wspólny sterownik wszystkich map rastrowych: filtry, dymki, powiększanie i przesuwanie.
   Obraz, pinezki i opisy korzystają z jednego stanu widoku, dlatego nie rozjeżdżają się
   po przeciągnięciu. Każda mapa ma własny stan i nie wpływa na pozostałe. */

(function ($) {
	'use strict';

	var POWIEKSZENIE_MAX = 2.2;
	var POWIEKSZENIE_MIN = 1;
	var KROK_POWIEKSZENIA = .3;
	var KROK_KLAWIATURY = 40;
	// Prześwit między krawędzią pinezki a dymkiem
	var PRZESWIT_DYMKA = 18.5;
	// Szerokość pinezki w makiecie
	var PINEZKA_MAKIETY = 55;

	$('.mapa-plansza').each(function (numerMapy) {
		var $plansza = $(this);
		var $obraz = $plansza.children('picture').first();
		var $punkty = $plansza.find('.pinezka, .pinezka-inwestycji, .punkt-mapy');
		var $dymki = $plansza.find('.dymek-mapy[data-dymek]');
		var $sterowanie = $plansza.find('.mapa-sterowanie');
		var powiekszenie = 1;
		var przesuniecieX = 0;
		var przesuniecieY = 0;
		var przeciaganie = null;

		if (!$obraz.length) {
			return;
		}

		$plansza.attr('tabindex', $plansza.attr('tabindex') || '0');

		function zapamietajPozycje($element) {
			if ($element.data('bazaX') !== undefined) {
				return;
			}
			$element.data('bazaX', parseFloat($element[0].style.left));
			$element.data('bazaY', parseFloat($element[0].style.top));
		}

		$punkty.each(function () {
			zapamietajPozycje($(this));
		});

		function pinezkaDla(nazwa) {
			return $plansza.find('[data-inwestycja="' + nazwa + '"]');
		}

		function dymekDla(nazwa) {
			return $plansza.find('.dymek-mapy[data-dymek="' + nazwa + '"]');
		}

		$dymki.each(function (indeks) {
			var $dymek = $(this);
			var $szczegoly = $dymek.children('.szczegoly');
			if (!$szczegoly.length) {
				return;
			}
			var identyfikator = 'szczegoly-mapy-' + numerMapy + '-' + indeks;
			$szczegoly.attr('id', identyfikator);
			pinezkaDla($dymek.data('dymek')).add($dymek.children('button.nazwa')).attr({
				'aria-controls': identyfikator,
				'aria-expanded': $dymek.hasClass('rozwiniety') ? 'true' : 'false'
			});
		});

		// Dymek nie ma własnej współrzędnej - stoi zawsze obok swojej pinezki i na jej wysokości
		function pozycjonujDymki() {
			$dymki.each(function () {
				var $dymek = $(this);
				var pinezka = pinezkaDla($dymek.data('dymek'))[0];
				if (!pinezka) {
					return;
				}
				if (!pinezka.offsetParent) {
					return;
				}
				var przeswit = window.innerWidth > 1024 && window.innerWidth < 1900
					? PRZESWIT_DYMKA * pinezka.offsetWidth / PINEZKA_MAKIETY
					: PRZESWIT_DYMKA;
				var odstep = pinezka.offsetWidth / 2 + przeswit;
				var x = $dymek.data('strona') === 'lewo'
					? pinezka.offsetLeft - odstep - this.offsetWidth
					: pinezka.offsetLeft + odstep;
				// Środek pinezki, bo dymek kotwiczy się środkiem (translateY(-50%) w LESS)
				var y = pinezka.offsetTop - pinezka.offsetHeight / 2
					+ (parseFloat($dymek.data('odsuniecie')) || 0);
				// Domykanie do kadru jest wspólne dla wszystkich map
				if (window.innerWidth <= 1024) {
					var margines = 8;
					var szerokoscDymka = $dymek.outerWidth();
					var polowaWysokosci = $dymek.outerHeight() / 2;
					// W prawym górnym rogu stoją kontrolki mapy. Gdy podpis wszedłby pod nie, przerzucamy go
					// na lewą stronę pinezki, nadal zachowując powiązanie.
					if (x + szerokoscDymka > $plansza.innerWidth() - 70 &&
						y - polowaWysokosci < 175) {
						x = pinezka.offsetLeft - odstep - szerokoscDymka;
					}
					x = Math.max(margines, Math.min($plansza.innerWidth() - szerokoscDymka - margines, x));
					y = Math.max(polowaWysokosci + margines,
						Math.min($plansza.innerHeight() - polowaWysokosci - margines, y));
					var pinSzer = pinezka.offsetWidth;
					var pinWys = pinezka.offsetHeight;
					var pinGora = pinezka.offsetTop - pinWys;
					var pinLewa = pinezka.offsetLeft - pinSzer / 2;
					var zachodziX = x < pinLewa + pinSzer && x + szerokoscDymka > pinLewa;
					var zachodziY = y - polowaWysokosci < pinezka.offsetTop && y + polowaWysokosci > pinGora;
					if (zachodziX && zachodziY) {
						var nadPinezka = pinGora - margines - polowaWysokosci;
						y = nadPinezka - polowaWysokosci >= margines
							? nadPinezka
							: pinezka.offsetTop + margines + polowaWysokosci;
						x = pinezka.offsetLeft - szerokoscDymka / 2;
						x = Math.max(margines, Math.min($plansza.innerWidth() - szerokoscDymka - margines, x));
						y = Math.max(polowaWysokosci + margines,
							Math.min($plansza.innerHeight() - polowaWysokosci - margines, y));
					}
				}
				this.style.setProperty('--dymek-x', x + 'px');
				this.style.setProperty('--dymek-y', y + 'px');
			});
		}

		function ograniczPrzesuniecie() {
			var element = $obraz[0];
			var styl = getComputedStyle(element);
			// Kadr wystaje z każdej strony o inną wartość, więc granice liczymy z prawdziwego
			// rozmiaru rastra i jego punktu skalowania.
			var poczatek = styl.transformOrigin.split(' ');
			var osX = parseFloat(poczatek[0]) || 0;
			var osY = parseFloat(poczatek[1]) || 0;
			var lewo = element.offsetLeft + osX * (1 - powiekszenie);
			var gora = element.offsetTop + osY * (1 - powiekszenie);
			var prawo = lewo + element.offsetWidth * powiekszenie;
			var dol = gora + element.offsetHeight * powiekszenie;
			var minimumX = Math.min(0, $plansza.innerWidth() - prawo);
			var maksimumX = Math.max(0, -lewo);
			var minimumY = Math.min(0, $plansza.innerHeight() - dol);
			var maksimumY = Math.max(0, -gora);

			przesuniecieX = Math.max(minimumX, Math.min(maksimumX, przesuniecieX));
			przesuniecieY = Math.max(minimumY, Math.min(maksimumY, przesuniecieY));

			return minimumX < maksimumX || minimumY < maksimumY;
		}

		function ustawPunkt($element) {
			var x = $element.data('bazaX');
			var y = $element.data('bazaY');
			if (isNaN(x) || isNaN(y)) {
				return;
			}
			x = 50 + (x - 50) * powiekszenie;
			y = 50 + (y - 50) * powiekszenie;
			$element.css({
				left: 'calc(' + x + '% + ' + przesuniecieX + 'px)',
				top: 'calc(' + y + '% + ' + przesuniecieY + 'px)'
			});
		}

		function odswiezWidok() {
			var moznaPrzesuwac = ograniczPrzesuniecie();
			$obraz.css('transform', 'translate3d(' + przesuniecieX + 'px, ' +
				przesuniecieY + 'px, 0) scale(' + powiekszenie + ')');
			$punkty.each(function () {
				ustawPunkt($(this));
			});
			pozycjonujDymki();
			$plansza.toggleClass('ma-powiekszenie', powiekszenie > 1);
			$plansza.toggleClass('ma-przesuwanie', moznaPrzesuwac);
		}

		function zmienPowiekszenie(roznica) {
			var nowe = Math.min(POWIEKSZENIE_MAX,
				Math.max(POWIEKSZENIE_MIN, powiekszenie + roznica));
			if (nowe === powiekszenie) {
				return;
			}
			powiekszenie = nowe;
			odswiezWidok();
		}

		// Filtry punktów w okolicy (podstrona inwestycji)
		var $filtryPunktow = $plansza.closest('section').find('.filtry-mapy-inwestycji');

		function odswiezPunkty() {
			if (!$filtryPunktow.length) {
				return;
			}
			var wybrane = [];
			$filtryPunktow.find('input:checked').each(function () {
				wybrane.push(this.value);
			});
			$plansza.find('.punkt-mapy').each(function () {
				$(this).toggle(wybrane.indexOf($(this).data('punkt')) !== -1);
			});
		}

		$filtryPunktow.on('change', 'input', odswiezPunkty);
		odswiezPunkty();

		function odswiezFiltry() {
			var $filtry = $plansza.closest('section').find('.filtry-mapy');
			if (!$filtry.length) {
				return;
			}
			var wybrane = [];
			$filtry.find('input:checked').each(function () {
				wybrane.push($(this).data('status'));
			});

			$plansza.find('.pinezka[data-status]').each(function () {
				var $pinezka = $(this);
				var widoczna = wybrane.indexOf($pinezka.data('status')) !== -1;
				var $dymek = $plansza.find('[data-dymek="' + $pinezka.data('inwestycja') + '"]');
				$pinezka.toggle(widoczna);
				$dymek.toggleClass('ukryty', !widoczna);
				if (!widoczna) {
					$dymek.removeClass('rozwiniety');
					$pinezka.removeClass('aktywna').attr('aria-expanded', 'false');
					$dymek.children('button.nazwa').attr('aria-expanded', 'false');
				}
			});
			// Pinezka włączona z powrotem dopiero teraz ma znowu swoje offsety.
			pozycjonujDymki();
		}

		var $filtry = $plansza.closest('section').find('.filtry-mapy');
		$filtry.on('change', 'input', odswiezFiltry);
		odswiezFiltry();

		// Poniżej 1025px dymek zmienia szerokość z animacją, a jego położenie zależy od szerokości.
		// Liczymy je więc w każdej klatce, aż animacja się skończy.
		var klatkaDymkow = null;

		function sledzDymki() {
			var koniec = Date.now() + 400;
			cancelAnimationFrame(klatkaDymkow);
			(function krok() {
				pozycjonujDymki();
				if (Date.now() < koniec) {
					klatkaDymkow = requestAnimationFrame(krok);
				}
			})();
		}

		$dymki.on('transitionend', function (e) {
			if (e.originalEvent.propertyName === 'width') {
				pozycjonujDymki();
			}
		});

		function zwinWszystkie() {
			$plansza.find('.dymek-mapy').removeClass('rozwiniety');
			$plansza.find('.pinezka, .dymek-mapy button.nazwa')
				.removeClass('aktywna').attr('aria-expanded', 'false');
			sledzDymki();
		}

		function przelacz(nazwa) {
			var $dymek = dymekDla(nazwa);
			var bylRozwiniety = $dymek.hasClass('rozwiniety');

			zwinWszystkie();

			if (!bylRozwiniety) {
				$dymek.addClass('rozwiniety');
				pinezkaDla(nazwa).addClass('aktywna').attr('aria-expanded', 'true');
				$dymek.children('button.nazwa').attr('aria-expanded', 'true');
			}
			sledzDymki();
		}

		// Pinezka i daszek przy nazwie robią to samo - to dwa wejścia w ten sam stan
		$plansza.on('click', '.pinezka[aria-controls]', function () {
			przelacz($(this).data('inwestycja'));
		});

		$plansza.on('click', '.dymek-mapy button.nazwa', function () {
			przelacz($(this).parent().data('dymek'));
		});

		$(document).on('click.mapa' + numerMapy, function (e) {
			if (!$(e.target).closest($plansza).length) {
				zwinWszystkie();
			}
		});

		$sterowanie.find('.zoom span').on('click keydown', function (e) {
			if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') {
				return;
			}
			e.preventDefault();
			zmienPowiekszenie($(this).index() === 0 ? KROK_POWIEKSZENIA : -KROK_POWIEKSZENIA);
		});

		$sterowanie.find('.gps').on('click', function () {
			powiekszenie = 1;
			przesuniecieX = 0;
			przesuniecieY = 0;
			odswiezWidok();
		});

		$plansza.on('pointerdown', function (e) {
			if (!$plansza.hasClass('ma-przesuwanie') || e.button !== 0 ||
				$(e.target).closest('.mapa-sterowanie, .pinezka, .dymek-mapy').length) {
				return;
			}
			e.preventDefault();
			przeciaganie = {
				id: e.originalEvent.pointerId,
				x: e.originalEvent.clientX,
				y: e.originalEvent.clientY,
				bazaX: przesuniecieX,
				bazaY: przesuniecieY
			};
			this.setPointerCapture(przeciaganie.id);
			$plansza.addClass('przeciagana');
		});

		$plansza.on('pointermove', function (e) {
			if (!przeciaganie || e.originalEvent.pointerId !== przeciaganie.id) {
				return;
			}
			przesuniecieX = przeciaganie.bazaX + e.originalEvent.clientX - przeciaganie.x;
			przesuniecieY = przeciaganie.bazaY + e.originalEvent.clientY - przeciaganie.y;
			odswiezWidok();
		});

		$plansza.on('pointerup pointercancel', function (e) {
			if (!przeciaganie || e.originalEvent.pointerId !== przeciaganie.id) {
				return;
			}
			przeciaganie = null;
			$plansza.removeClass('przeciagana');
		});

		$plansza.on('keydown', function (e) {
			if (!$plansza.hasClass('ma-przesuwanie') ||
				$(e.target).is('a, button, [role="button"]')) {
				return;
			}
			var ruchX = 0;
			var ruchY = 0;
			if (e.key === 'ArrowLeft') { ruchX = KROK_KLAWIATURY; }
			if (e.key === 'ArrowRight') { ruchX = -KROK_KLAWIATURY; }
			if (e.key === 'ArrowUp') { ruchY = KROK_KLAWIATURY; }
			if (e.key === 'ArrowDown') { ruchY = -KROK_KLAWIATURY; }
			if (!ruchX && !ruchY) {
				return;
			}
			e.preventDefault();
			przesuniecieX += ruchX;
			przesuniecieY += ruchY;
			odswiezWidok();
		});

		$(window).on('resize.mapa' + numerMapy, odswiezWidok);
		odswiezWidok();
		// Pierwsze liczenie idzie jeszcze na foncie zastępczym
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(function () {
				if (window.innerWidth <= 1024) {
					odswiezWidok();
				}
			});
		}
	});

})(jQuery);
