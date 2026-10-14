{{-- Wiersz lokalu: wariant „inwestycja” - widok listy na podstronie inwestycji (inwestycja.html, .lista-lokali),
     „wyszukiwarka” - wyniki ze wszystkich inwestycji (wyszukiwarka.html: nazwa inwestycji, adres, cena za m², schowek).
     Atrybuty data-* - filtry, sortowanie i schowek w glowny.js. --}}
@props(['lokal', 'wariant' => 'inwestycja'])
@php
	[$akcja, $adresAkcji] = $lokal->akcja();
	$wyszukiwarka = $wariant === 'wyszukiwarka';
	$ikonaWiersza = $wyszukiwarka ? 19 : 24;
@endphp
<li class="wiersz-lokalu element-oferty" @foreach($lokal->daneFiltra() as $klucz => $wartosc) data-{{ $klucz }}="{{ $wartosc }}" @endforeach>
	<div class="komorka">
		<a class="nazwa-lokalu" href="{{ $lokal->link() }}">{{ $lokal->nazwaTypu() }} <span class="akcent">{{ $lokal->numerLokalu() }}</span></a>
		@if($wyszukiwarka)
			<span class="inwestycja">{{ mb_strtoupper($lokal->investment->name) }}</span>
		@endif
	</div>
	<div class="komorka">
		@if($wyszukiwarka)
			<span class="linia"><img src="{{ asset('img/ikona-adres.svg') }}" width="19" height="19" alt=""> {{ \App\Support\FiltryOferty::lokalizacja($lokal->investment) }}</span>
		@endif
		@if($lokal->pokazujPietro())
			<span class="linia pietro"><img src="{{ asset('img/lokal-ikona-pietro.svg') }}" width="19" height="19" alt=""> Piętro: <strong>{{ $lokal->pietro() }}</strong></span>
		@endif
	</div>
	<div class="komorka">
		<span class="linia"><img src="{{ asset('img/lokal-ikona-metraz.svg') }}" width="{{ $ikonaWiersza }}" height="{{ $ikonaWiersza }}" alt=""> Powierzchnia:</span>
		<span class="linia mocna">{{ $lokal->metraz() }}</span>
	</div>
	<div class="komorka">
		@if($lokal->pokazujPokoje())
			<span class="linia"><img src="{{ asset('img/lokal-ikona-pokoje.svg') }}" width="{{ $ikonaWiersza }}" height="{{ $ikonaWiersza }}" alt=""> Liczba pokoi:</span>
			<span class="linia mocna">{{ $lokal->rooms }}</span>
		@endif
	</div>
	@if($wyszukiwarka)
		<div class="komorka">
			<span class="cena">{{ $lokal->maCene() ? $lokal->cena() : ($lokal->sprzedany() ? '—' : 'Zapytaj o cenę') }}</span>
			@if($lokal->cenaMetra())
				<span class="cena-metr">{{ $lokal->cenaMetra() }}</span>
			@endif
		</div>
		<span @class(['status', $lokal->statusKlasa() => $lokal->statusKlasa() !== 'dostepne'])>{{ $lokal->statusNapis() }}</span>
		<button class="do-ulubionych" type="button" data-ulubione-id="{{ $lokal->idUlubionych() }}"
			data-nazwa-ulubionego="{{ $lokal->nazwaPelna() }} – {{ $lokal->investment->name }}"
			aria-label="Dodaj {{ mb_strtolower($lokal->nazwaPelna()) }} do ulubionych">
			<img src="{{ asset('img/ikona-serce.svg') }}" width="25" height="25" alt="">
		</button>
		<a class="przycisk-sprawdz" href="{{ $lokal->link() }}">SPRAWDŹ</a>
	@else
		<span class="cena">{{ $lokal->maCene() ? $lokal->cena() : ($lokal->sprzedany() ? '—' : 'Zapytaj o cenę') }}</span>
		<span class="status {{ $lokal->statusKlasa() }}">{{ $lokal->statusNapis() }}</span>
		<div class="akcje-wiersza">
			<a class="przycisk-sprawdz" href="{{ $adresAkcji }}">{{ $akcja }}</a>
			<a class="przycisk-sprawdz" href="{{ $lokal->link() }}">SPRAWDŹ</a>
		</div>
	@endif
</li>
