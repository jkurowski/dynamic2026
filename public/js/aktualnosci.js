(function ($) {
	'use strict';

	var $lista = $('.lista-wpisow');
	var $paginacja = $('.paginacja');
	if (!$lista.length || !$paginacja.length) {
		return;
	}

	// Kartami są kolumny wiersza siatki, a nie sam wiersz. Klasa przygaszenia zostaje na
	// sekcji, bo to ona ma przejście w arkuszu stylów.
	var $tor = $lista.children('.row');
	var karty = $tor.children().toArray();
	var liczbaStron = 8;
	var bezRuchu = window.matchMedia &&
		window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function poprawNumer(numer) {
		return Math.max(1, Math.min(liczbaStron, parseInt(numer, 10) || 1));
	}

	function uloz(numer) {
		var przesuniecie = (numer - 1) % karty.length;
		var kolejnosc = karty.slice(przesuniecie).concat(karty.slice(0, przesuniecie));
		$tor.append(kolejnosc);
	}

	function widoczneNumery(numer) {
		if (numer <= 3 || numer >= 6) {
			return [1, 2, 3, null, 6, 7, 8];
		}
		if (numer === 4) {
			return [1, 2, 3, 4, null, 7, 8];
		}
		return [1, 2, null, 5, 6, 7, 8];
	}

	function odnosnikStrony(numer, biezaca) {
		return '<a class="numer-strony' + (biezaca ? ' biezaca' : '') +
			'" href="?strona=' + numer + '" data-strona="' + numer + '"' +
			(biezaca ? ' aria-current="page"' : '') + '>' + numer + '</a>';
	}

	function zaznacz(numer) {
		var html = [];
		if (numer > 1) {
			html.push('<a class="poprzednia" href="?strona=' + (numer - 1) +
				'" data-strona="' + (numer - 1) + '" aria-label="Poprzednia strona">' +
				// ten sam rysunek co w strzałce w prawo, tylko odbity w arkuszu stylów
				'<img src="/img/ikona-strzalka-prawo.svg" width="26" height="27" alt=""></a>');
		}

		widoczneNumery(numer).forEach(function (wartosc) {
			html.push(wartosc === null ? '<span class="przerwa">...</span>' :
				odnosnikStrony(wartosc, wartosc === numer));
		});

		if (numer < liczbaStron) {
			html.push('<a class="nastepna" href="?strona=' + (numer + 1) +
				'" data-strona="' + (numer + 1) + '" aria-label="Następna strona">' +
				'<img src="/img/ikona-strzalka-prawo.svg" width="26" height="27" alt=""></a>');
		}
		$paginacja.html(html.join(''));
	}

	function ustaw(numer, animuj) {
		numer = poprawNumer(numer);
		function zakoncz() {
			uloz(numer);
			zaznacz(numer);
			$lista.removeClass('zmienia-strone');
		}

		if (!animuj || bezRuchu) {
			zakoncz();
			return;
		}

		$lista.addClass('zmienia-strone').one('transitionend', function (zdarzenie) {
			if (zdarzenie.originalEvent.propertyName === 'opacity') {
				zakoncz();
			}
		});
	}

	var poczatkowa = poprawNumer(new URLSearchParams(window.location.search).get('strona'));
	ustaw(poczatkowa, false);

	$paginacja.on('click', 'a[data-strona]', function (zdarzenie) {
		zdarzenie.preventDefault();
		var numer = poprawNumer($(this).attr('data-strona'));
		history.pushState({strona: numer}, '', '?strona=' + numer);
		ustaw(numer, true);
	});

	$(window).on('popstate', function () {
		ustaw(new URLSearchParams(window.location.search).get('strona'), false);
	});
})(jQuery);
