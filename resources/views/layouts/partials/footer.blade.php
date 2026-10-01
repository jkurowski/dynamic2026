@php
    // Menu stopki. Aktywna pozycja wyliczana z adresu (w szablonie klasa "aktywny" na bieżącej podstronie).
    $menuStopki = [
        ['nazwa' => 'Strona główna', 'link' => route('index'), 'aktywny' => request()->routeIs('index')],
        ['nazwa' => 'Inwestycje', 'link' => route('menu.show', ['uri' => 'inwestycje']), 'aktywny' => request()->is('inwestycje*')],
        ['nazwa' => 'Wybierz mieszkanie', 'link' => route('menu.show', ['uri' => 'wyszukiwarka']), 'aktywny' => request()->is('wyszukiwarka*')],
        ['nazwa' => 'Poznaj nas', 'link' => route('menu.show', ['uri' => 'poznaj-nas']), 'aktywny' => request()->is('poznaj-nas*')],
        ['nazwa' => 'Aktualności', 'link' => route('aktualnosci.index'), 'aktywny' => request()->is('aktualnosci*')],
        ['nazwa' => 'Kontakt', 'link' => route('contact'), 'aktywny' => request()->is('kontakt*')],
    ];
@endphp
<!-- ============ STOPKA ============ -->
<footer class="stopka">
	<div class="stopka-uklad">

		<div class="stopka-marka">
			<img class="logo-stopki" src="{{ asset('img/logo-stopka.svg') }}" width="286" height="78" alt="Dynamic Development">
			<p class="haslo">Kreujemy nowoczesną rzeczywistość</p>
			<div class="stopka-social">
				<a href="https://www.instagram.com/dynamicdevelopmentpolska/" target="_blank" rel="noopener" aria-label="Instagram">
					<img src="{{ asset('img/ikona-instagram-stopka.svg') }}" width="28" height="28" alt="">
				</a>
				<a href="https://www.facebook.com/DynamicDevelopmentPolska/" target="_blank" rel="noopener" aria-label="Facebook">
					<img src="{{ asset('img/ikona-facebook.svg') }}" width="28" height="28" alt="">
				</a>
			</div>
		</div>

		<nav class="stopka-kolumna" aria-labelledby="stopka-menu">
			<h2 id="stopka-menu"><img src="{{ asset('img/ikona-pinezka.svg') }}" width="12" height="19" alt=""> MENU</h2>
			<ul>
				@foreach($menuStopki as $pozycja)
					<li><a @class(['aktywny' => $pozycja['aktywny']]) href="{{ $pozycja['link'] }}">{{ $pozycja['nazwa'] }}</a></li>
				@endforeach
			</ul>
		</nav>

		<nav class="stopka-kolumna" aria-labelledby="stopka-inwestycje">
			<h2 id="stopka-inwestycje"><img src="{{ asset('img/ikona-pinezka.svg') }}" width="12" height="19" alt=""> INWESTYCJE</h2>
			<ul>
				<li><a href="{{ route('menu.show', ['uri' => 'inwestycje']) }}">Dom Hygge Twin</a></li>
				<li><a href="{{ route('menu.show', ['uri' => 'inwestycje']) }}">Konstancin Riverside House</a></li>
				<li><a href="{{ route('menu.show', ['uri' => 'inwestycje']) }}">Lake Village</a></li>
				<li><a href="{{ route('menu.show', ['uri' => 'inwestycje']) }}">Zielona Polana</a></li>
			</ul>
		</nav>

		<div class="stopka-kolumna" aria-labelledby="stopka-kontakt">
			<h2 id="stopka-kontakt"><img src="{{ asset('img/ikona-pinezka.svg') }}" width="12" height="19" alt=""> KONTAKT</h2>
			<p>
				Dynamic Development sp. z o.o.<br>
				ul. Plonowa 24, <span class="kod-pocztowy">05-515</span> Nowa Wola<br>
				KRS: 0000257514<br>
				NIP: 5213389378<br>
				REGON: 140557694
			</p>
		</div>

	</div>

	<p class="pasek-praw">Copyright © {{ date('Y') }} Dynamic Development All Rights Reserved</p>
</footer>
