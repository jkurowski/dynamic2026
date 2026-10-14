{{-- Kafelek lokalu na podstronie inwestycji (szablon inwestycja.html, .karta-mieszkania). Atrybuty data-* - filtry w glowny.js. --}}
@props(['lokal', 'opoznienie' => null])
@php [$akcja, $adresAkcji] = $lokal->akcja(); @endphp
<li class="col-12 col-sm-6 col-md-3 element-oferty" @foreach($lokal->daneFiltra() as $klucz => $wartosc) data-{{ $klucz }}="{{ $wartosc }}" @endforeach>
	<article @class(['karta-mieszkania', 'pojawia-sie', $opoznienie])>
		<div class="rzut">
			<picture>
				<source type="image/webp" srcset="{{ $lokal->rzut('thumbs', true) }}">
				<img src="{{ $lokal->rzut('thumbs') }}" width="215" height="218" alt="Rzut - {{ $lokal->nazwaPelna() }}" loading="lazy">
			</picture>
		</div>
		<div class="paski">
			<span class="status {{ $lokal->statusKlasa() }}">{{ $lokal->statusNapis() }}</span>
			<a class="oblicz-rate" href="{{ $adresAkcji }}">{{ $akcja }}</a>
		</div>
		<ul class="parametry-mieszkania">
			<li>
				<img src="{{ asset('img/lokal-ikona-metraz.svg') }}" width="24" height="24" alt="">
				<span class="nazwa">POWIERZCHNIA</span>
				<span class="wartosc">{{ $lokal->metraz() }}</span>
			</li>
			@if($lokal->pokazujPokoje())
			<li>
				<img src="{{ asset('img/lokal-ikona-pokoje.svg') }}" width="24" height="24" alt="">
				<span class="nazwa">LICZBA POKOI</span>
				<span class="wartosc">{{ $lokal->rooms }}</span>
			</li>
			@endif
			@if($lokal->pokazujPietro())
			<li>
				<img src="{{ asset('img/lokal-ikona-pietro.svg') }}" width="24" height="24" alt="">
				<span class="nazwa">PIĘTRO</span>
				<span class="wartosc">{{ $lokal->pietro() }}</span>
			</li>
			@endif
			<li>
				<img src="{{ asset('img/inw-ikona-cena.svg') }}" width="24" height="24" alt="">
				<span class="nazwa">CENA</span>
				<span class="wartosc">{{ $lokal->maCene() ? $lokal->cena() : ($lokal->sprzedany() ? '—' : 'Zapytaj o cenę') }}</span>
			</li>
		</ul>
		<div class="stopka-mieszkania">
			<a class="nazwa" href="{{ $lokal->link() }}">{{ $lokal->nazwaTypu() }} <span class="akcent">{{ $lokal->numerLokalu() }}</span></a>
			<a class="strzalka" href="{{ $lokal->link() }}" aria-label="Zobacz {{ mb_strtolower($lokal->nazwaPelna()) }}">
				<img src="{{ asset('img/strzalka-nastepna-duza.svg') }}" width="24" height="25" alt="">
			</a>
		</div>
	</article>
</li>
