/* Kalkulator raty kredytu z podstrony "Finansowanie".

   Dwa suwaki (kwota i okres) plus stałe oprocentowanie. Po każdym ruchu przeliczamy
   ratę wzorem na ratę równą (annuitetową):

       rata = K * m / (1 - (1 + m)^(-n))

   gdzie K to kwota kredytu, m to oprocentowanie miesięczne (roczne / 12),
   a n to liczba miesięcy. Ten sam wzór stosują banki dla rat równych. */

(function ($) {
	'use strict';

	var $kalkulator = $('#kalkulatorRaty');
	if (!$kalkulator.length) {
		return;
	}

	var OPROCENTOWANIE = 6.7;   // procent w skali roku, wartość z projektu

	var $kwota = $kalkulator.find('[name="kwota"]');
	var $okres = $kalkulator.find('[name="okres"]');
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
		var latKredytu = parseInt($okres.val(), 10);
		var miesiecy = latKredytu * 12;
		var miesieczne = OPROCENTOWANIE / 100 / 12;

		var rata = kwota * miesieczne / (1 - Math.pow(1 + miesieczne, -miesiecy));

		$opisKwoty.text(formatKwoty === 'grosze-kropki' ? zlotowkiZKropkami(kwota) : bezGroszy(kwota));
		$opisOkresu.text(lata(latKredytu));
		// Makieta pokazuje konkretną wartość startową
		$rata.text(pierwszyPomiar ? wynikPoczatkowy : zlotowkiZKropkami(rata));

		pomaluj($kwota);
		pomaluj($okres);
	}

	$kalkulator.on('input change', 'input[type="range"]', function () {
		pierwszyPomiar = false;
		przelicz();
	});
	przelicz();

})(jQuery);
