@extends('layouts.page')

{{-- Wybierz nieruchomość - oferta ze wszystkich inwestycji (szablon dynamic-front/wyszukiwarka.html).
     ?ulubione=1 - schowek: glowny.js zostawia tylko lokale zapisane w przeglądarce i zmienia nagłówek. --}}
@section('main_class', 'ma-pasek-boczny')
@section('meta_title', $page->title ?? 'Wybierz nieruchomość')
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset

@section('content')
	<!-- ============ NAGŁÓWEK PODSTRONY ============ -->
	<section class="naglowek-strony naglowek-wynikow">
		<x-okruszki :sciezka="['Wybierz nieruchomość' => null]" />
		<x-etykieta class="na-srodku pojawia-sie">WYSZUKIWARKA</x-etykieta>
		<h1 class="tytul-sekcji na-srodku rozjasnia-sie">Wybierz <span class="akcent">nieruchomość</span></h1>
	</section>

		<x-pasek-boczny />

	<!-- ============ FILTRY ============ -->
	<x-inwestycje.filtry :filtry="$filtry" klasa="filtry-wynikow" class="pojawia-sie" />

	<!-- ============ PASEK NAD WYNIKAMI ============ -->
	<div class="pasek-wynikow pojawia-sie">
		<p>Liczba wyników: <strong class="liczba-wynikow">{{ $inwestycje->sum(fn ($i) => $i->lokaleNaFroncie()->count()) }}</strong></p>
		<div class="sortowanie dropdown">
			<label for="sortowanie">Sortuj według:</label>
			<button class="pole-sortowania" type="button" id="sortowanie" data-bs-toggle="dropdown" aria-expanded="false">
				<span class="wybrane-sortowanie">Wybierz</span>
				<span class="ikona-sortowania" aria-hidden="true"></span>
			</button>
			<ul class="dropdown-menu lista-filtru lista-sortowania">
				<li><button class="dropdown-item wybrany" type="button">Wybierz</button></li>
				<li><button class="dropdown-item" type="button">Cena rosnąco</button></li>
				<li><button class="dropdown-item" type="button">Cena malejąco</button></li>
				<li><button class="dropdown-item" type="button">Powierzchnia rosnąco</button></li>
				<li><button class="dropdown-item" type="button">Powierzchnia malejąco</button></li>
			</ul>
		</div>
	</div>

	<!-- ============ WYNIKI POGRUPOWANE PO INWESTYCJACH ============ -->
	@foreach($inwestycje as $inwestycja)
		<section class="grupa-wynikow pojawia-sie" data-inwestycja="{{ $inwestycja->name }}">
			<div class="baner-grupy">
				<picture>
					<source type="image/webp" srcset="{{ $inwestycja->zdjecieKarty(true) }}">
					<img src="{{ $inwestycja->zdjecieKarty() }}" width="1680" height="150" alt="" loading="lazy">
				</picture>
				<div class="opis">
					<h2>{{ $inwestycja->name }}</h2>
					<p class="lokalizacja"><img src="{{ asset('img/ikona-adres.svg') }}" width="26" height="26" alt=""> {{ \App\Support\FiltryOferty::lokalizacja($inwestycja) }}</p>
				</div>
				<a class="przycisk-inwestycji" href="{{ $inwestycja->link() }}">SPRAWDŹ INWESTYCJĘ</a>
			</div>
			<ul class="lista-lokali">
				@foreach($inwestycja->lokaleNaFroncie() as $lokal)
					<x-inwestycje.wiersz-lokalu :lokal="$lokal" wariant="wyszukiwarka" />
				@endforeach
			</ul>
		</section>
	@endforeach
@endsection
