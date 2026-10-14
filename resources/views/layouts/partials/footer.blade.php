@php
    // Menu stopki. Aktywna pozycja wyliczana z adresu (w szablonie klasa "aktywny" na bieżącej podstronie).
    $menuStopki = [
        ['nazwa' => 'Strona główna', 'link' => route('index'), 'aktywny' => request()->routeIs('index')],
        ['nazwa' => 'Inwestycje', 'link' => route('inwestycje.index'), 'aktywny' => request()->is('inwestycje*')],
        ['nazwa' => 'Wybierz nieruchomość', 'link' => route('wyszukiwarka'), 'aktywny' => request()->is('wybierz-nieruchomosc*')],
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
				@foreach(\App\Models\Investment::frontu() as $inwestycjaStopki)
					<li><a href="{{ $inwestycjaStopki->linkMenu() }}" @if($inwestycjaStopki->linkZewnetrzny()) target="_blank" rel="noopener" @endif>{{ $inwestycjaStopki->name }}</a></li>
				@endforeach
			</ul>
		</nav>

		{{-- Edycja: sekcja „stopka.kontakt” (config/sekcje.php) --}}
		@php $kontaktStopki = sekcja('stopka.kontakt'); @endphp
		<div class="stopka-kolumna" aria-labelledby="stopka-kontakt"{!! $kontaktStopki->edycja() !!}>
			<h2 id="stopka-kontakt"><img src="{{ asset('img/ikona-pinezka.svg') }}" width="12" height="19" alt=""> {{ $kontaktStopki->tekst('naglowek') }}</h2>
			<p>
				{!! $kontaktStopki->html('dane') !!}
			</p>
		</div>

	</div>

	<p class="pasek-praw">Copyright © {{ date('Y') }} Dynamic Development All Rights Reserved</p>
</footer>
