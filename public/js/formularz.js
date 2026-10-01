/* Walidacja formularza kontaktowego.
   W projekcie nie ma zaprojektowanych stanów błędu - komunikaty i kolory są nasze.
   Formularz ma atrybut novalidate, żeby wyłączyć domyślne dymki przeglądarki.
   Błędne pole zapala się obrysem, a treść wszystkich komunikatów ląduje w jednym
   miejscu pod przyciskiem, żeby nie rozpychać formularza. */

(function ($) {
	'use strict';

	var $formularz = $('#formularzKontaktowy');
	if (!$formularz.length) {
		return;
	}

	// Reguły dla poszczególnych pól. Zwracają true, gdy wartość jest poprawna.
	var reguly = {
		imie: function (v) { return v.trim().length >= 3; },
		telefon: function (v) { return v.replace(/\D/g, '').length >= 9; },
		email: function (v) { return v === '' || /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i.test(v); },
		wiadomosc: function (v) { return v.trim().length >= 10; }
	};

	function sprawdzPole($pole) {
		var $wejscie = $pole.find('input, textarea');
		var nazwa = $wejscie.attr('name');
		var regula = reguly[nazwa];
		if (!regula) {
			return true;
		}

		var poprawne = regula($wejscie.val());
		$pole.toggleClass('ma-blad', !poprawne).toggleClass('poprawne', poprawne && $wejscie.val() !== '');
		return poprawne;
	}

	// Treści komunikatów siedzą przy polach w HTML-u, więc nie ma ich w skrypcie.
	function zbierzBledy() {
		var lista = [];

		$formularz.find('.pole.ma-blad .blad').each(function () {
			lista.push($(this).text());
		});

		if ($formularz.find('.zgoda.ma-blad').length) {
			lista.push('Zaznacz wymaganą zgodę na przetwarzanie danych.');
		}

		return lista;
	}

	function pokazBledy() {
		var $komunikat = $formularz.find('.komunikat-formularza');
		var lista = zbierzBledy();

		if (!lista.length) {
			$komunikat.attr('class', 'komunikat-formularza').empty();
			return;
		}

		var $lista = $('<ul>');
		$.each(lista, function (i, tresc) {
			$lista.append($('<li>').text(tresc));
		});
		$komunikat.attr('class', 'komunikat-formularza blad').empty().append($lista);
	}

	// Sprawdzamy dopiero po opuszczeniu pola - podpowiadanie w trakcie pisania irytuje.
	$formularz.on('blur', 'input, textarea', function () {
		sprawdzPole($(this).closest('.pole'));
	});

	$formularz.on('input', '.ma-blad input, .ma-blad textarea', function () {
		sprawdzPole($(this).closest('.pole'));
		odswiez();
	});

	// Zgodę zaznaczamy dopiero po nieudanej wysyłce - przed nią odznaczone pole to normalny
	// stan, a nie błąd.
	$formularz.on('change', '[name="zgoda-rodo"]', function () {
		if (!$formularz.find('.komunikat-formularza').hasClass('blad')) {
			return;
		}
		$(this).closest('.zgoda').toggleClass('ma-blad', !$(this).is(':checked'));
		pokazBledy();
	});

	function odswiez() {
		if ($formularz.find('.komunikat-formularza').hasClass('blad')) {
			pokazBledy();
		}
	}

	$formularz.on('submit', function (e) {
		var wszystkoOk = true;

		$formularz.find('.pole').each(function () {
			if (!sprawdzPole($(this))) {
				wszystkoOk = false;
			}
		});

		// zgoda RODO jest obowiązkowa
		var $zgoda = $formularz.find('[name="zgoda-rodo"]');
		var zgodaOk = $zgoda.is(':checked');
		$zgoda.closest('.zgoda').toggleClass('ma-blad', !zgodaOk);
		if (!zgodaOk) {
			wszystkoOk = false;
		}

		var $komunikat = $formularz.find('.komunikat-formularza');

		if (!wszystkoOk) {
			e.preventDefault();
			pokazBledy();
			$formularz.find('.ma-blad').first().find('input, textarea').trigger('focus');
			return;
		}

		// Wysyłkę obsłuży backend
		e.preventDefault();
		$komunikat.attr('class', 'komunikat-formularza sukces').empty()
			.text('Dziękujemy, wiadomość została przygotowana do wysyłki.');
	});

})(jQuery);
