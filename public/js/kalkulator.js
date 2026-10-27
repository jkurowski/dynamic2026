/* Kalkulator raty kredytu z podstrony "Finansowanie".

   Dwa suwaki (kwota i okres) plus stałe oprocentowanie. Po każdym ruchu przeliczamy
   ratę wzorem na ratę równą (annuitetową):

       rata = K * m / (1 - (1 + m)^(-n))

   gdzie K to kwota kredytu, m to oprocentowanie miesięczne (roczne / 12),
   a n to liczba miesięcy. Ten sam wzór stosują banki dla rat równych.

   Oprocentowanie z atrybutu data-oprocentowanie (panel: Sekcje stron -> Kalkulator raty).
   Karta lokalu ma trzeci suwak - wkład własny w % ceny: K = cena - wkład. */

(function ($) {
	'use strict';

	var $kalkulator = $('#kalkulatorRaty');
	if (!$kalkulator.length) {
		return;
	}

	// procent w skali roku; 6,7 - wartość z projektu, gdyby atrybutu zabrakło
	var OPROCENTOWANIE = parseFloat($kalkulator.attr('data-oprocentowanie')) || 6.7;

	var $kwota = $kalkulator.find('[name="kwota"]');
	var $okres = $kalkulator.find('[name="okres"]');
	var $wklad = $kalkulator.find('[name="wklad"]');
	var $opisWkladu = $kalkulator.find('.wartosc-wkladu');
	var $opisKredytu = $kalkulator.find('.wartosc-kredytu');
	var $opisKwoty = $kalkulator.find('.wartosc-kwoty');
	var $opisOkresu = $kalkulator.find('.wartosc-okresu');
	var $rata = $kalkulator.find('.wynik-raty');
	var wynikPoczatkowy = $kalkulator.attr('data-wynik-poczatkowy');
	var formatKwoty = $kalkulator.attr('data-format-kwoty');
	var pierwszyPomiar = Boolean(wynikPoczatkowy);

	// W obu makietach tysiące rozdziela kropka, a nie spacja: "500.000 zł", "3.786,97 zł".
	function zKropkami(liczba) {
		return String(liczba).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
	}

	function bezGroszy(liczba) {
		return zKropkami(Math.round(liczba)) + ' zł';
	}

	function zlotowkiZKropkami(liczba) {
		var czesci = liczba.toFixed(2).split('.');
		return zKropkami(czesci[0]) + ',' + czesci[1] + ' zł';
	}

	// Odmiana słowa "rok" - inaczej wychodzi "20 rok" albo "22 lat".
	function lata(ile) {
		if (ile === 1) {
			return '1 rok';
		}
		var reszta = ile % 10;
		var setka = ile % 100;
		if (reszta >= 2 && reszta <= 4 && (setka < 12 || setka > 14)) {
			return ile + ' lata';
		}
		return ile + ' lat';
	}

	// Suwak sam z siebie nie pokazuje, ile jest "przejechane", więc kolorujemy tło
	// gradientem do miejsca, w którym stoi uchwyt.
	function pomaluj($suwak) {
		var min = parseFloat($suwak.attr('min'));
		var max = parseFloat($suwak.attr('max'));
		var procent = ((parseFloat($suwak.val()) - min) / (max - min)) * 100;
		$suwak.css('--wypelnienie', procent + '%');
	}

	function przelicz() {
		var kwota = parseFloat($kwota.val());
		var procentWkladu = $wklad.length ? parseInt($wklad.val(), 10) : 0;
		var wklad = kwota * procentWkladu / 100;
		var kredyt = kwota - wklad;
		var latKredytu = parseInt($okres.val(), 10);
		var miesiecy = latKredytu * 12;
		var miesieczne = OPROCENTOWANIE / 100 / 12;

		var rata = kredyt * miesieczne / (1 - Math.pow(1 + miesieczne, -miesiecy));

		$opisKwoty.text(formatKwoty === 'grosze-kropki' ? zlotowkiZKropkami(kwota) : bezGroszy(kwota));
		$opisOkresu.text(lata(latKredytu));
		$opisWkladu.text(procentWkladu + '% (' + bezGroszy(wklad) + ')');
		$opisKredytu.text(zlotowkiZKropkami(kredyt));
		// Makieta pokazuje konkretną wartość startową
		$rata.text(pierwszyPomiar ? wynikPoczatkowy : zlotowkiZKropkami(rata));

		pomaluj($kwota);
		pomaluj($okres);
		if ($wklad.length) {
			pomaluj($wklad);
		}
	}

	$kalkulator.on('input change', 'input[type="range"]', function () {
		pierwszyPomiar = false;
		przelicz();
	});
	przelicz();

})(jQuery);
