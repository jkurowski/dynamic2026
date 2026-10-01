/* Dwie proste karuzele: galeria inwestycji i lista aktualności.
   Obie działają tak samo - przesuwają zawartość o jeden element w bok, więc logika
   siedzi w jednej funkcji, a nie w dwóch prawie identycznych kopiach.

   Samo przestawienie elementu w DOM daje efekt "przeskoku". Dlatego najpierw przesuwamy całą taśmę przez transform, a dopiero
   po zakończeniu przejazdu przestawiamy element i zerujemy przesunięcie bez animacji.
   Dla oka wygląda to jak jeden płynny ruch. */

(function ($) {
	'use strict';

	var CZAS = 450;   // ms - tyle trwa przejazd taśmy

	var bezRuchu = window.matchMedia &&
		window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	// O ile trzeba przesunąć taśmę
	function krok($tor) {
		var dzieci = $tor.children();
		if (dzieci.length < 2) {
			return 0;
		}
		return dzieci.eq(1)[0].offsetLeft - dzieci.eq(0)[0].offsetLeft;
	}

	// Taśma o kaflach różnej szerokości bywa ledwie szersza od kadru
	function przybij($tor) {
		if ($tor.css('justify-content').indexOf('center') < 0) {
			return;
		}

		// offsetLeft, nie getBoundingClientRect
		var przed = $tor.children().first()[0].offsetLeft;
		$tor.css('justify-content', 'flex-start');
		var po = $tor.children().first()[0].offsetLeft;

		$tor.css('margin-left',
			((parseFloat($tor.css('margin-left')) || 0) + (przed - po)) + 'px');
	}

	// Kadr, w którym widać taśmę
	function kadr($tor) {
		var el = $tor[0].parentElement;

		while (el && el !== document.documentElement) {
			if (window.getComputedStyle(el).overflowX !== 'visible') {
				return el.getBoundingClientRect();
			}
			el = el.parentElement;
		}

		return $tor[0].getBoundingClientRect();
	}

	// Kopie doklejone na początek przesuwają całą taśmę w prawo o swoją szerokość
	function doklejZPrzodu($tor, $kopie) {
		var punkt = $tor.children().first()[0];
		var przed = punkt.offsetLeft;

		$tor.prepend($kopie);
		$tor[0].style.setProperty('--wysuniecie', (przed - punkt.offsetLeft) + 'px');
	}

	// Kopie taśmy
	function kopie($elementy) {
		var wejscia = '.pojawia-sie, .rozjasnia-sie, .kolejno';
		var $kopie = $elementy.clone().attr('aria-hidden', 'true');

		$kopie.filter(wejscia).addClass('widoczny');
		$kopie.find(wejscia).addClass('widoczny');

		return $kopie;
	}

	function dopelnij($tor) {
		var ramka = kadr($tor);
		var $oryginaly = $tor.children();
		var lewa = Infinity;
		var prawa = -Infinity;

		$oryginaly.each(function () {
			var pole = this.getBoundingClientRect();
			lewa = Math.min(lewa, pole.left - ramka.left);
			prawa = Math.max(prawa, pole.right - ramka.left);
		});

		// Zapas z prawej strony kadru musi wystarczyć na jeden przejazd
		if (prawa - ramka.width < krok($tor)) {
			przybij($tor);
			$tor.append(kopie($oryginaly));
		}

		// Taśma, która zaczyna się dokładnie przy krawędzi kadru, nie ma czym zasłonić lewego
		// brzegu
		if (lewa > 0) {
			doklejZPrzodu($tor, kopie($oryginaly));
		}
	}

	// $tor    - element, którego dzieci przewijamy $wstecz, $naprzod - przyciski
	function karuzela($tor, $wstecz, $naprzod) {
		if (!$tor.length) {
			return;
		}

		var $elementy = $tor.children();
		// Taśma zdjęć ma na każdej szerokości aktywny kadr większy od sąsiadów
		function tasmaZeSkalowaniem() {
			return $tor.parent().is('.galeria-realizacji, .galeria-inwestycji');
		}
		if ($elementy.length < 2) {
			$wstecz.add($naprzod).prop('disabled', true).css('opacity', .45);
			return;
		}

		// Kopie przygotowujemy, gdy taśma wjedzie w kadr, a nie przy pierwszym kliknięciu
		var dopelnione = false;

		function przygotuj() {
			if (dopelnione) {
				return;
			}

			if (!$naprzod.is(':visible')) {
				return;
			}

			var czekajace = $tor.find('img').toArray().filter(function (zdjecie) {
				return !zdjecie.complete;
			});

			if (czekajace.length) {
				$(czekajace).one('load error', przygotuj);
				return;
			}

			// Na wąskich ekranach część kafli jest schowana i nie ma z czego mierzyć skoku - tam
			// karuzela i tak przestawia zdjęcia bez przejazdu.
			if (krok($tor) <= 0) {
				return;
			}

			dopelnij($tor);
			dopelnione = true;
		}

		if ('IntersectionObserver' in window) {
			var wypatrywacz = new IntersectionObserver(function (wpisy) {
				if (!wpisy[0].isIntersecting) {
					return;
				}
				wypatrywacz.disconnect();
				przygotuj();
			}, { threshold: 0 });
			wypatrywacz.observe($tor[0]);
		}

		var jedzie = false;   // blokada na czas przejazdu, żeby szybkie klikanie nie gubiło kroków
		var zapasPrzejscia;   // id zapasowego timeoutu z poPrzejsciu

		// pierwszy element wędruje na koniec albo odwrotnie - dzięki temu karuzela jest "w
		// kółko" bez kopiowania elementów w HTML
		function przestaw(kierunek) {
			if (kierunek > 0) {
				$tor.append($tor.children().first());
			} else {
				$tor.prepend($tor.children().last());
			}
		}

		function ustaw(przesuniecie, zAnimacja) {
			$tor.css({
				transition: zAnimacja ? ('transform ' + CZAS + 'ms ' +
					(tasmaZeSkalowaniem() ? 'cubic-bezier(.77, 0, .175, 1)' : 'ease')) : 'none',
				transform: przesuniecie ? ('translateX(' + przesuniecie + 'px)') : 'none'
			});
		}

		// Podpowiedź dla przeglądarki, żeby przygotowała warstwę pod przejazd
		function warstwa(wlaczona) {
			$tor.css('will-change', wlaczona ? 'transform' : '');
		}

		function poPrzejsciu(akcja) {
			var zrobione = false;

			function zakoncz(e) {
				if (e && e.originalEvent.propertyName !== 'transform') {
					return;
				}
				if (zrobione) {
					return;
				}
				zrobione = true;
				clearTimeout(zapasPrzejscia);
				$tor.off('transitionend.karuzela', zakoncz);
				akcja();
			}

			zapasPrzejscia = setTimeout(zakoncz, CZAS + 80);
			$tor.on('transitionend.karuzela', zakoncz);
		}

		function przesun(kierunek) {
			if (jedzie) {
				return;
			}

			// Przestawiamy od razu, bez przejazdu, w dwóch przypadkach
			if (bezRuchu || krok($tor) <= 0) {
				przestaw(kierunek);
				return;
			}

			// Zapas na wypadek, gdyby ktoś kliknął, zanim taśma zdążyła się przygotować.
			przygotuj();

			var dystans = krok($tor);

			jedzie = true;
			warstwa(true);

			if (kierunek > 0) {
				// W przód
				if (tasmaZeSkalowaniem()) {
					$tor.addClass('jedzie-naprzod');
				}
				ustaw(-dystans, true);
				poPrzejsciu(function () {
					przestaw(1);
					ustaw(0, false);
					$tor.removeClass('jedzie-naprzod');
					warstwa(false);
					jedzie = false;
				});
			} else {
				// W tył odwrotnie: ostatni element wskakuje na początek, taśmę odsuwamy od razu o jego
				// szerokość i dopiero wtedy wraca na miejsce.
				przestaw(-1);
				ustaw(-krok($tor), false);
				if (tasmaZeSkalowaniem()) {
					$tor.addClass('przed-wstecz');
				}
				void $tor[0].offsetWidth;
				$tor.removeClass('przed-wstecz');
				ustaw(0, true);
				poPrzejsciu(function () {
					warstwa(false);
					jedzie = false;
				});
			}
		}

		$naprzod.on('click', function () { przesun(1); });
		$wstecz.on('click', function () { przesun(-1); });

		function resetuj() {
			oczekujacyReset = null;
			$tor.off('transitionend.karuzela');
			clearTimeout(zapasPrzejscia);
			$tor.children('[aria-hidden="true"]').remove();
			$tor.removeClass('jedzie-naprzod przed-wstecz')
				.css({ 'margin-left': '', 'justify-content': '', transform: '', transition: '' });
			$tor[0].style.removeProperty('--wysuniecie');
			jedzie = false;
			warstwa(false);
			dopelnione = false;
			przygotuj();
		}

		var oczekujacyReset = null;
		$(window).on('resize', function () {
			if (oczekujacyReset) {
				cancelAnimationFrame(oczekujacyReset);
			}
			oczekujacyReset = requestAnimationFrame(resetuj);
		});
	}

	/* Galeria inwestycji na stronie głównej działa inaczej niż taśma realizacji:
	   obok dużego zdjęcia stoją przyciemnione miniatury.
	   Tutaj strzałka albo kliknięcie w miniaturę wymienia ją z dużym zdjęciem. */
	function galeriaInwestycji() {
		var $glowna = $('.galeria-glowna picture').first();
		var $tor = $('.galeria-miniatury');

		if (!$glowna.length || $tor.children().length < 1) {
			return;
		}

		// Opis inwestycji po lewej stronie sekcji
		var $opis = $('.inwestycje-opis');

		var MIEJSCE_NA_TYTUL = 758;

		function dopasujTytul($tytul) {
			var el = $tytul[0];

			$tytul.css('font-size', '');
			// Poza układem desktopowym tytuł normalnie się zawija i nic tu nie robimy.
			if (!el || $tytul.css('white-space') !== 'nowrap') {
				return;
			}

			var rozmiar = parseFloat($tytul.css('font-size'));
			while (rozmiar > 36 && el.scrollWidth > MIEJSCE_NA_TYTUL) {
				rozmiar -= 1;
				$tytul.css('font-size', rozmiar + 'px');
			}
		}

		// Wpisanie opisu jest osobno od jego odczytania
		function wpiszOpis(dane) {
			if (!dane || !dane.tytul) {
				return;
			}
			$opis.find('.tytul-sekcji').text(dane.tytul);
			dopasujTytul($opis.find('.tytul-sekcji'));
			$opis.find('.inwestycje-podtytul').text(dane.podtytul);
			$opis.find('.parametry li').first().find('.nazwa').text(dane.miasto);
			$opis.find('.parametry li').first().find('.wartosc').text(dane.adres);
			$opis.find('.inwestycje-przycisk').attr('href', dane.link);
			$glowna.closest('.galeria-glowna').find('.tekst-adresu').text(dane.pasek);
		}

		function opiszInwestycje($zdjecie) {
			wpiszOpis($zdjecie.data());
		}

		// Opis zmienia się razem z przejazdem, a nie po nim
		var CZAS_OPISU = Math.round(CZAS * .8);

		function elementyOpisu() {
			return $opis.find('.tytul-sekcji, .inwestycje-podtytul, .parametry')
				.add($glowna.closest('.galeria-glowna').find('.tekst-adresu'));
		}

		function zapowiedzOpis($zdjecie, juzWpisany) {
			var dane = $.extend({}, $zdjecie.data());

			if (zmieniaSie || !dane.tytul || bezRuchu || !Element.prototype.animate) {
				return;
			}

			elementyOpisu().each(function () {
				this.animate(juzWpisany
					? [{ opacity: 0 }, { opacity: 1 }]
					: [{ opacity: 1 }, { opacity: 0 }, { opacity: 1 }],
				{ duration: CZAS_OPISU, easing: 'ease-in-out' });
			});

			if (juzWpisany) {
				return;
			}

			// Podmiana w połowie drogi, czyli dokładnie wtedy, gdy tekst jest niewidoczny.
			setTimeout(function () {
				wpiszOpis(dane);
			}, Math.round(CZAS_OPISU / 2));
		}

		// Rozwijanie szczegółów ma wysokość, a nie samo pojawienie się
		var CZAS_PANELU = 250;        // = @czas-animacji z less/zmienne.less
		var PROG_SZCZEGOLOW = 1400;   // przełącznik stoi tylko poniżej @desktop-xxl
		var zwijanie;

		function pokazSzczegoly(otwarte) {
			var $panel = $opis.find('.inwestycje-szczegoly-panel');
			var panel = $panel[0];

			clearTimeout(zwijanie);

			if (!panel || bezRuchu || window.innerWidth >= PROG_SZCZEGOLOW) {
				$panel.css('height', '');
				$opis.toggleClass('ma-otwarte-szczegoly', otwarte);
				return;
			}

			// Drogę zaczynamy od tego, co widać teraz, a nie od wysokości treści
			var poczatek = panel.getBoundingClientRect().height;

			if (otwarte) {
				$opis.addClass('ma-otwarte-szczegoly');
				$panel.css('height', poczatek + 'px');
				void panel.offsetHeight;
				$panel.css('height', panel.scrollHeight + 'px');
				// Po wjeździe oddajemy wysokość treści: panel musi móc urosnąć, gdy karuzela wpisze
				// dłuższy opis albo gdy zmieni się szerokość okna.
				zwijanie = setTimeout(function () {
					$panel.css('height', '');
				}, CZAS_PANELU);
				return;
			}

			$panel.css('height', poczatek + 'px');
			void panel.offsetHeight;
			$panel.css('height', 0);
			zwijanie = setTimeout(function () {
				$opis.removeClass('ma-otwarte-szczegoly');
				$panel.css('height', '');
			}, CZAS_PANELU);
		}

		$opis.on('click', '.inwestycje-przelacznik-szczegolow', function () {
			var $przelacznik = $(this);
			var otwarte = $przelacznik.attr('aria-expanded') !== 'true';
			$przelacznik
				.attr('aria-expanded', otwarte ? 'true' : 'false')
				.attr('aria-label', otwarte ? 'Ukryj szczegóły inwestycji' : 'Pokaż szczegóły inwestycji');
			pokazSzczegoly(otwarte);
		});

		function wymien($a, $b) {
			var $ia = $a.find('img'), $ib = $b.find('img');
			var $sa = $a.find('source'), $sb = $b.find('source');
			var chwila;

			chwila = $ia.attr('src'); $ia.attr('src', $ib.attr('src')); $ib.attr('src', chwila);
			chwila = $ia.attr('alt'); $ia.attr('alt', $ib.attr('alt')); $ib.attr('alt', chwila);
			chwila = $sa.attr('srcset'); $sa.attr('srcset', $sb.attr('srcset')); $sb.attr('srcset', chwila);

			var daneA = $.extend({}, $a.data()), daneB = $.extend({}, $b.data());
			$a.removeData().data(daneB);
			$b.removeData().data(daneA);

			opiszInwestycje($a);
		}

		var zmieniaSie = false;

		function animujPrzesunieciem(kierunek, akcja) {
			if (window.innerWidth >= 1400 || bezRuchu || !Element.prototype.animate) {
				akcja();
				return;
			}
			if (zmieniaSie) {
				return;
			}

			zmieniaSie = true;
			var $wchodzaca = kierunek > 0 ? $tor.children().first() : $tor.children().last();
			var $odchodzaca = kierunek > 0 ? $tor.children().last() : $tor.children().first();
			// Karuzela ma wymiary taśmy z galerii inwestycji, a te zmieniają się z szerokością okna,
			// więc odległość i skalę bierzemy z ułożonych kadrów
			var glowna = $glowna[0].getBoundingClientRect();
			var boczna = $wchodzaca[0].getBoundingClientRect();
			var dystans = Math.abs(boczna.left + boczna.width / 2 - glowna.left - glowna.width / 2);
			var skala = boczna.width / glowna.width;
			var przesuniecie = (kierunek > 0 ? -dystans : dystans) + 'px';
			var opcje = {
				duration: CZAS,
				easing: 'cubic-bezier(.77, 0, .175, 1)',
				fill: 'both'
			};
			var animacje = [
				$glowna[0].animate([
					{ translate: '0 0', scale: '1' },
					{ translate: przesuniecie + ' 0', scale: String(skala) }
				], opcje),
				$wchodzaca[0].animate([
					{ translate: '0 0', scale: String(skala) },
					{ translate: przesuniecie + ' 0', scale: '1' }
				], opcje),
				$odchodzaca[0].animate([
					{ translate: '0 0', scale: String(skala) },
					{ translate: przesuniecie + ' 0', scale: String(skala) }
				], opcje)
			];

			// Kadr, który po przestawieniu stanie z boku, wjeżdża zza krawędzi razem z taśmą
			var $nowa = kierunek > 0 ? $tor.children().eq(1) : $tor.children().eq(-2);
			if ($nowa.length && !$nowa.is($wchodzaca) && !$nowa.is($odchodzaca) &&
				!$nowa.is(':visible')) {
				$nowa.css({ display: 'block', left: getComputedStyle($wchodzaca[0]).left, right: 'auto' });
				animacje.push($nowa[0].animate([
					{ translate: (kierunek > 0 ? dystans : -dystans) + 'px 0' },
					{ translate: '0 0' }
				], opcje));
			}

			Promise.all(animacje.map(function (animacja) {
				return animacja.finished.catch(function () {});
			})).then(function () {
				akcja();
				animacje.forEach(function (animacja) { animacja.cancel(); });
				$tor.children().css({ display: '', left: '', right: '' });
				zmieniaSie = false;
			});
		}

		// --- kolaż na dużym desktopie (od 1400px) ---
		function odczyt(el) {
			var zdjecie = el.querySelector('img');

			return {
				pole: el.getBoundingClientRect(),
				kadr: zdjecie.getBoundingClientRect(),
				maska: window.getComputedStyle(el).clipPath
			};
		}

		// Kadry kolażu w kolejności
		function sloty() {
			var lista = [odczyt($glowna[0])];

			$tor.children(':visible').each(function () {
				lista.push(odczyt(this));
			});

			return lista;
		}

		// Element stojący w kadrze "z" udaje kadr "na"
		function lec(el, z, na, opcje, odwrotnie) {
			var sx = na.pole.width / z.pole.width;
			var sy = na.pole.height / z.pole.height;
			var pudelko = [
				{ transformOrigin: '0 0', transform: 'none', clipPath: z.maska },
				{
					transformOrigin: '0 0',
					clipPath: na.maska,
					transform: 'translate(' + (na.pole.left - z.pole.left) + 'px,' +
						(na.pole.top - z.pole.top) + 'px) scale(' + sx + ',' + sy + ')'
				}
			];
			var fotografia = [
				{ transformOrigin: '0 0', transform: 'none' },
				{
					transformOrigin: '0 0',
					transform: 'translate(' +
						((na.kadr.left - na.pole.left) / sx - (z.kadr.left - z.pole.left)) + 'px,' +
						((na.kadr.top - na.pole.top) / sy - (z.kadr.top - z.pole.top)) + 'px) scale(' +
						(na.kadr.width / sx / z.kadr.width) + ',' +
						(na.kadr.height / sy / z.kadr.height) + ')'
				}
			];

			if (odwrotnie) {
				pudelko.reverse();
				fotografia.reverse();
			}

			return [
				el.animate(pudelko, opcje),
				el.querySelector('img').animate(fotografia, opcje)
			];
		}

		// Miniatura przy krawędzi ekranu wjeżdża zza niej (albo za nią odjeżdża) razem z resztą
		// kolażu. Bez tego ukryty kadr pojawiał się dopiero po przelocie. Na czas ruchu stoi
		// w miejscu ostatniej miniatury z jej wymiarami i kadrowaniem zdjęcia.
		function zapiszKrawedz(kadry) {
			var $widoczne = $tor.children(':visible');
			var ostatnia = $widoczne.last()[0];
			var styl = window.getComputedStyle(ostatnia.querySelector('img'));

			return {
				wzor: kadry[kadry.length - 1],
				krok: kadry[kadry.length - 1].pole.left - kadry[kadry.length - 2].pole.left,
				zdjecie: { left: styl.left, top: styl.top, width: styl.width, height: styl.height },
				ostatnia: ostatnia,
				ukryta: $tor.children()[$widoczne.length]
			};
		}

		function przyKrawedzi(krawedz, opcje, wjezdza) {
			var el = wjezdza ? krawedz.ukryta : krawedz.ostatnia;
			var wzor = krawedz.wzor;
			var przesuniecie = krawedz.krok + 'px 0';

			if (!el) {
				return null;
			}

			$(el).css({
				display: 'block', position: 'absolute', left: 0, top: 0, margin: 0, flex: 'none',
				width: wzor.pole.width + 'px', height: wzor.pole.height + 'px', clipPath: wzor.maska
			});
			var pole = el.getBoundingClientRect();
			$(el).css({ left: wzor.pole.left - pole.left + 'px', top: wzor.pole.top - pole.top + 'px' });
			$(el).find('img').css(krawedz.zdjecie);

			return el.animate(wjezdza
				? [{ translate: przesuniecie }, { translate: '0 0' }]
				: [{ translate: '0 0' }, { translate: przesuniecie }], opcje);
		}

		function sprzatnijKrawedz(krawedz) {
			$([krawedz.ostatnia, krawedz.ukryta]).css({
				display: '', position: '', left: '', top: '', margin: '', flex: '',
				width: '', height: '', clipPath: ''
			}).find('img').css({ left: '', top: '', width: '', height: '' });
		}

		// przeloty - funkcja zwracająca listę [element, kadr startowy, kadr docelowy]
		function kolazem(przeloty, akcja, poZamianie, przygaszGlowna) {
			if (zmieniaSie) {
				return;
			}
			zmieniaSie = true;

			// Pasek z adresem i plakietka opisują duży kadr i mają zostać na wierzchu
			var $wierzch = $glowna.parent().find('.pasek-adresu, .plakietka-status');
			var opcje = { duration: CZAS, easing: 'cubic-bezier(.77, 0, .175, 1)', fill: 'both' };
			var kadry = sloty();
			var animacje;
			// Strzałki przesuwają cały kolaż o jeden kadr, więc zmienia się miniatura przy krawędzi
			var krawedz = przygaszGlowna && kadry.length > 2 ? zapiszKrawedz(kadry) : null;

			$wierzch.css('z-index', 4);

			if (poZamianie) {
				akcja();
			}

			animacje = przeloty(kadry).reduce(function (lista, przelot) {
				return lista.concat(lec(przelot[0], przelot[1], przelot[2], opcje, poZamianie));
			}, []);

			// Dopiero po wyliczeniu przelotów, bo pokazany kadr liczyłby się jako widoczny
			if (krawedz) {
				animacje.push(przyKrawedzi(krawedz, opcje, !poZamianie));
				animacje = animacje.filter(Boolean);
			}

			// Zdjęcie, na które wjeżdża następny kadr, gasło dopiero na końcu przelotu
			if (przygaszGlowna) {
				animacje.push($glowna[0].animate(
					poZamianie ? [{ opacity: 0 }, { opacity: 1 }] : [{ opacity: 1 }, { opacity: 0 }],
					opcje));
			}

			Promise.all(animacje.map(function (animacja) {
				return animacja.finished.catch(function () {});
			})).then(function () {
				if (!poZamianie) {
					akcja();
				}
				animacje.forEach(function (animacja) { animacja.cancel(); });
				if (krawedz) {
					sprzatnijKrawedz(krawedz);
				}
				$wierzch.css('z-index', '');
				zmieniaSie = false;
			});
		}

		function oJedenKadr(kadry) {
			return $tor.children(':visible').map(function (numer, el) {
				return [[el, kadry[numer + 1], kadry[numer]]];
			}).get();
		}

		// Kolaż obsługujemy tylko wtedy, gdy jest z czego mierzyć: potrzebny jest choć jeden
		// widoczny kadr obok dużego zdjęcia.
		function kolazDziala() {
			return window.innerWidth >= 1400 && !bezRuchu && Element.prototype.animate &&
				$tor.children(':visible').length > 0;
		}

		function zezZmiana(akcja) {
			if (zmieniaSie) {
				return;
			}
			if (bezRuchu) {
				akcja();
				return;
			}
			zmieniaSie = true;
			$glowna.addClass('gasnie');
			$glowna.one('transitionend', function (e) {
				if (e.originalEvent.propertyName !== 'opacity') {
					return;
				}
				akcja();
				$glowna.removeClass('gasnie');
				$glowna.one('transitionend', function () {
					zmieniaSie = false;
				});
			});
		}

		$('.galeria-nastepna').on('click', function () {
			var akcja = function () {
				var $pierwsza = $tor.children().first();
				wymien($glowna, $pierwsza);
				$tor.append($pierwsza);        // użyta miniatura idzie na koniec kolejki
			};

			zapowiedzOpis($tor.children().first());

			if (kolazDziala()) {
				kolazem(oJedenKadr, akcja, false, true);
			} else if (window.innerWidth < 1400) {
				animujPrzesunieciem(1, akcja);
			} else {
				zezZmiana(akcja);
			}
		});

		$('.galeria-poprzednia').on('click', function () {
			var akcja = function () {
				var $ostatnia = $tor.children().last();
				$tor.prepend($ostatnia);
				wymien($glowna, $ostatnia);
			};

			zapowiedzOpis($tor.children().last(), kolazDziala());

			if (kolazDziala()) {
				kolazem(oJedenKadr, akcja, true, true);
			} else if (window.innerWidth < 1400) {
				animujPrzesunieciem(-1, akcja);
			} else {
				zezZmiana(akcja);
			}
		});

		// Kliknięcie w konkretną miniaturę wrzuca ją na duże zdjęcie
		$tor.on('click', 'picture', function () {
			var $ta = $(this);
			var akcja = function () { wymien($glowna, $ta); };

			zapowiedzOpis($ta);

			if (!kolazDziala()) {
				zezZmiana(akcja);
				return;
			}

			kolazem(function (kadry) {
				var numer = $tor.children(':visible').index($ta) + 1;

				return [
					[$ta[0], kadry[numer], kadry[0]],
					[$glowna[0], kadry[0], kadry[numer]]
				];
			}, akcja, false);
		});

		// Nazwa z HTML-a też musi się zmieścić, a po zmianie szerokości okna zmienia się i
		// rozmiar czcionki, i to, czy wiersz w ogóle jest nierozrywany.
		dopasujTytul($opis.find('.tytul-sekcji'));
		$(window).on('resize', function () {
			dopasujTytul($opis.find('.tytul-sekcji'));
		});
	}

	galeriaInwestycji();

	// Przewijamy wiersz siatki, a nie sekcję - karty są jego kolumnami
	karuzela(
		$('.aktualnosci-kadr').children('.row'),
		$('.aktualnosci-poprzednia'),
		$('.aktualnosci-nastepna')
	);

	// galeria realizacji na podstronie "Wykończenie pod klucz"
	karuzela(
		$('.tasma-realizacji'),
		$('.realizacje-poprzednia'),
		$('.realizacje-nastepna')
	);

	// Zakładki galerii na podstronie inwestycji
	$('.zakladki-galerii').on('click', 'button', function () {
		var $przycisk = $(this);
		var zdjecia = ($przycisk.data('zdjecia') || '').split(',');
		var $tasma = $przycisk.closest('.galeria-inwestycji').find('.tasma-realizacji');

		if (zdjecia.length < 3 || $przycisk.hasClass('wybrana')) {
			return;
		}

		$przycisk.addClass('wybrana').siblings().removeClass('wybrana');

		function podmien() {
			// Reszta z dzielenia, bo po przewinięciu taśma może mieć komplet kopii - wtedy każde
			// zdjęcie z zakładki trafia na wszystkie swoje miejsca.
			$tasma.children('picture').each(function (indeks) {
				var nazwa = (zdjecia[indeks % zdjecia.length] || '').trim();
				if (!nazwa) {
					return;
				}
				$(this).find('source').attr('srcset', '/img/' + nazwa + '.webp');
				$(this).find('img')
					.attr('src', '/img/' + nazwa + '.jpg')
					.attr('alt', 'Zdjęcie z galerii: ' + $przycisk.text().trim().toLowerCase());
			});
		}

		if (bezRuchu) {
			podmien();
			return;
		}

		$tasma.addClass('gasnie');
		setTimeout(function () {
			podmien();
			$tasma.removeClass('gasnie');
		}, 200);
	});

	karuzela(
		$('.tasma-historii'),
		$('.historia-poprzednia'),
		$('.historia-nastepna')
	);

})(jQuery);
