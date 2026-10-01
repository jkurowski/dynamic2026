/* Walidacja i wysyłka formularza kontaktowego (komponent x-formularz-kontaktowy).
   W projekcie nie ma zaprojektowanych stanów błędu - komunikaty i kolory są nasze.
   Formularz ma atrybut novalidate, żeby wyłączyć domyślne dymki przeglądarki.
   Błędne pole zapala się obrysem, a treść wszystkich komunikatów ląduje w jednym
   miejscu pod przyciskiem, żeby nie rozpychać formularza.

   Wysyłka: po poprawnej walidacji formularz idzie zwykłym POST-em na Front\ContactController@send.
   Gdy w panelu ustawiono klucze reCAPTCHA (atrybut data-recaptcha), przed wysyłką pobieramy
   token v3 do pola g-recaptcha-response. Błędy walidacji serwera i komunikat sukcesu
   renderuje Blade - po przeładowaniu przewijamy do formularza. */
(function ($) {
	'use strict';

	var $formularz = $('#formularzKontaktowy');
	if (!$formularz.length) {
		return;
	}

	// Reguły dla poszczególnych pól. Zwracają true, gdy wartość jest poprawna.
	var reguly = {
		name: function (v) { return v.trim().length >= 3; },
		phone: function (v) { return v.replace(/\D/g, '').length >= 9; },
		email: function (v) { return v === '' || /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i.test(v); },
		message: function (v) { return v.trim().length >= 10; }
	};

	// Wymagane zgody RODO (klauzule z panelu, pola rule_{id}) mają atrybut data-wymagana.
	var $zgodyWymagane = $formularz.find('input[data-wymagana]');

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

	// Komunikat może być <p> (pusty, sukces) albo <div> (błędy serwera) - lista błędów musi stać w <div>.
	function komunikat() {
		return $formularz.find('.komunikat-formularza');
	}

	function pokazBledy() {
		var lista = zbierzBledy();
		if (!lista.length) {
			komunikat().attr('class', 'komunikat-formularza').empty();
			return;
		}
		var $lista = $('<ul>');
		$.each(lista, function (i, tresc) {
			$lista.append($('<li>').text(tresc));
		});
		var $nowy = $('<div class="komunikat-formularza blad" role="status">').append($lista);
		komunikat().replaceWith($nowy);
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
	$formularz.on('change', 'input[data-wymagana]', function () {
		if (!komunikat().hasClass('blad')) {
			return;
		}
		$(this).closest('.zgoda').toggleClass('ma-blad', !$(this).is(':checked'));
		pokazBledy();
	});

	function odswiez() {
		if (komunikat().hasClass('blad')) {
			pokazBledy();
		}
	}

	var wysylanie = false;

	$formularz.on('submit', function (e) {
		var wszystkoOk = true;

		$formularz.find('.pole').each(function () {
			if (!sprawdzPole($(this))) {
				wszystkoOk = false;
			}
		});

		$zgodyWymagane.each(function () {
			var zaznaczona = $(this).is(':checked');
			$(this).closest('.zgoda').toggleClass('ma-blad', !zaznaczona);
			if (!zaznaczona) {
				wszystkoOk = false;
			}
		});

		if (!wszystkoOk) {
			e.preventDefault();
			pokazBledy();
			$formularz.find('.ma-blad').first().find('input, textarea').trigger('focus');
			return;
		}

		// Druga wysyłka tego samego zgłoszenia (podwójne kliknięcie) - blokujemy
		if (wysylanie) {
			e.preventDefault();
			return;
		}

		var kluczRecaptcha = $formularz.data('recaptcha');
		if (kluczRecaptcha && window.grecaptcha) {
			e.preventDefault();
			wysylanie = true;
			$formularz.find('.formularz-przycisk').prop('disabled', true);
			grecaptcha.ready(function () {
				grecaptcha.execute(kluczRecaptcha, { action: 'submitContact' }).then(function (token) {
					$formularz.find('[name="g-recaptcha-response"]').val(token);
					$formularz.get(0).submit(); // natywny submit - bez ponownego wejścia w ten handler
				}, function () {
					wysylanie = false;
					$formularz.find('.formularz-przycisk').prop('disabled', false);
				});
			});
			return;
		}

		// Zwykła wysyłka - przeglądarka wysyła formularz
		wysylanie = true;
		$formularz.find('.formularz-przycisk').prop('disabled', true);
	});

	// Po przeładowaniu z komunikatem (sukces albo błędy serwera) - przewiń do formularza.
	// Przy "Wyślij i wróć" wracamy na stronę, na której formularz stoi zwykle na dole.
	if (komunikat().is('.sukces, .blad')) {
		var gora = $formularz.offset().top - ($('.naglowek').outerHeight() || 0) - 40;
		$('html, body').scrollTop(Math.max(gora, 0));
	}
})(jQuery);
