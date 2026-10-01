/* Mobilne ujawnianie aktualności na stronie głównej. Wysokość jest mierzona z DOM,
   więc liczba kart może zmieniać się bez aktualizowania CSS-u. */
(function ($) {
	'use strict';

	var PROG_MOBILNY = 1024;
	var $kadr = $('#aktualnosciDomowe');
	var $przycisk = $('.aktualnosci-wiecej');
	var animacjaZaplanowana = false;

	if (!$kadr.length || !$przycisk.length) {
		return;
	}

	function karty() {
		return $kadr.find('> .row > div').filter(function () {
			return !this.hasAttribute('aria-hidden');
		});
	}

	function zmierz() {
		if (window.innerWidth > PROG_MOBILNY) {
			$kadr.removeClass('rozwiniete pomiar-gotowy').css('max-height', '');
			$przycisk.attr('aria-expanded', 'false');
			return;
		}

		var $karty = karty();
		if ($karty.length < 2) {
			$kadr.css('max-height', 'none');
			$przycisk.prop('hidden', true);
			return;
		}

		$przycisk.prop('hidden', false);
		var druga = $karty.eq(1)[0];
		var wysokoscZwinieta = druga.offsetTop + druga.offsetHeight * .5;
		$kadr[0].style.setProperty('--wysokosc-zwinieta', wysokoscZwinieta + 'px');
		$kadr[0].style.setProperty('--wysokosc-zanikania', (druga.offsetHeight * .5) + 'px');
		$kadr.css('max-height', $kadr.hasClass('rozwiniete') ? $kadr[0].scrollHeight : wysokoscZwinieta);

		if (!$kadr.hasClass('pomiar-gotowy') && !animacjaZaplanowana) {
			animacjaZaplanowana = true;
			requestAnimationFrame(function () {
				animacjaZaplanowana = false;
				if (window.innerWidth <= PROG_MOBILNY) {
					$kadr.addClass('pomiar-gotowy');
				}
			});
		}
	}

	$przycisk.on('click', function () {
		var rozwiniete = !$kadr.hasClass('rozwiniete');
		$kadr.toggleClass('rozwiniete', rozwiniete);
		$przycisk.attr('aria-expanded', rozwiniete ? 'true' : 'false').html(
			'<span aria-hidden="true">/</span> ' + (rozwiniete ? 'POKAŻ MNIEJ' : 'POKAŻ WIĘCEJ'));
		$kadr.css('max-height', rozwiniete ? $kadr[0].scrollHeight :
			parseFloat($kadr[0].style.getPropertyValue('--wysokosc-zwinieta')));
	});

	var zaplanowane = null;
	$(window).on('resize', function () {
		if (zaplanowane) cancelAnimationFrame(zaplanowane);
		zaplanowane = requestAnimationFrame(function () {
			zaplanowane = null;
			zmierz();
		});
	});

	$kadr.find('img').on('load', zmierz);
	zmierz();
})(jQuery);
