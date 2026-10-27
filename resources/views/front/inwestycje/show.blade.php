@extends('layouts.page')

{{-- Podstrona inwestycji (szablon dynamic-front/inwestycja.html).
     Z bazy: nagłówek, parametry, lokale z filtrami (kafelki/lista). Treści (opis, okolica, atuty, galeria) - $tresci. --}}
@section('meta_title', $inwestycja->name)
@section('seo_title', $inwestycja->meta_title ?: $inwestycja->name . ' - Dynamic Development')
@if($inwestycja->meta_description) @section('seo_description', $inwestycja->meta_description) @endif

@php
	$lokalizacja = \App\Support\FiltryOferty::lokalizacja($inwestycja);
	$produkt = $inwestycja->produkt();
	$parametry = array_filter([
		$inwestycja->zakresMetrazu() ? ['ikona-metraz.svg', 'ZAKRES METRAŻU:', $inwestycja->zakresMetrazu()] : null,
		$inwestycja->cenaOd() ? ['ikona-cena.svg', 'CENY OD', $inwestycja->cenaOd()] : null,
		$inwestycja->terminOddania() ? ['ikona-kalendarz.svg', 'TERMIN ODDANIA:', $inwestycja->terminOddania()] : null,
	]);
	$zakladki = array_filter([
		'mieszkania' => 'Znajdź ' . $produkt,
		'opis' => $tresci ? 'Opis inwestycji' : null,
		'lokalizacja' => $tresci ? 'Lokalizacja' : null,
		'atuty' => $tresci ? 'Atuty inwestycji' : null,
		'galeria' => $tresci ? 'Galeria' : null,
		'finansowanie' => 'Finansowanie',
		'kontakt' => 'Kontakt',
	]);
@endphp

@section('content')
	<!-- ============ NAGŁÓWEK INWESTYCJI ============ -->
	<section class="inwestycja-hero">
		<div class="fin-hero-tresc">
			<x-okruszki :sciezka="['Inwestycje' => route('inwestycje.index'), $inwestycja->name => null]" />
			<div class="inwestycja-hero-kolumna pojawia-sie">
				<x-etykieta>{{ collect([$inwestycja->address, $lokalizacja ? str_replace(', ', ' - ', $lokalizacja) : null])->filter()->implode(', ') }}</x-etykieta>
				<h1>{{ $inwestycja->name }}</h1>
				<p class="wstep">@includeFirst(['front.inwestycje.tresci.' . $inwestycja->slug . '-wstep', 'front.inwestycje.tresci.brak-wstepu'], ['inwestycja' => $inwestycja])</p>
				@if($parametry)
					<ul class="parametry-inwestycji pojawia-sie">
						@foreach($parametry as [$ikona, $nazwa, $wartosc])
							<li>
								<img src="{{ asset('img/' . $ikona) }}" width="34" height="34" alt="">
								<span class="tresc"><span class="nazwa">{{ $nazwa }}</span><span class="wartosc">{{ $wartosc }}</span></span>
							</li>
						@endforeach
					</ul>
				@endif
				<div class="para-przyciskow pojawia-sie opoznienie-1">
					<a href="#mieszkania" class="przycisk-pigulka pelny">
						ZNAJDŹ {{ mb_strtoupper($produkt) }}
					</a>
					@if($tresci)
						<x-przycisk-pigulka href="#opis" class="na-tle">OPIS INWESTYCJI</x-przycisk-pigulka>
					@endif
				</div>
			</div>
		</div>
		<div class="inwestycja-zdjecie pojawia-sie z-prawej opoznienie-1">
			<picture>
				<source type="image/webp" srcset="{{ $inwestycja->zdjecieNaglowka(true) }}">
				<img src="{{ $inwestycja->zdjecieNaglowka() }}" width="1019" height="650" alt="Wizualizacja inwestycji {{ $inwestycja->name }}" fetchpriority="high">
			</picture>
		</div>
		<x-pasek-boczny />
	</section>

	<!-- ============ PASEK ZAKŁADEK ============ -->
	<nav class="zakladki-inwestycji" aria-label="Sekcje inwestycji">
		<ul>
			@foreach($zakladki as $kotwica => $napis)
				<li @class(['aktywna' => $loop->first])><a href="#{{ $kotwica }}">{{ $napis }}</a></li>
			@endforeach
		</ul>
	</nav>

	<!-- ============ ZNAJDŹ MIESZKANIE ============ -->
	<section class="szukaj-mieszkan" id="mieszkania">
		<x-etykieta class="na-srodku pojawia-sie">{{ $produkt === 'mieszkanie' ? 'APARTAMENTY' : 'OFERTA' }}</x-etykieta>
		<h2 class="tytul-sekcji na-srodku rozjasnia-sie">Znajdź {{ $produkt }} <span class="akcent">dla siebie</span></h2>
		<x-inwestycje.filtry :filtry="$filtry" klasa="filtry-inwestycji" :widok="true" />
	</section>
	<div class="kontener-mieszkan">
		<ul class="lista-mieszkan kolejno row list-unstyled">
			@foreach($lokale as $lokal)
				<x-inwestycje.karta-lokalu :lokal="$lokal" :opoznienie="$loop->index % 4 ? 'opoznienie-' . ($loop->index % 4) : null" />
			@endforeach
		</ul>
		<ul class="lista-lokali">
			@foreach($lokale as $lokal)
				<x-inwestycje.wiersz-lokalu :lokal="$lokal" wariant="inwestycja" />
			@endforeach
		</ul>
		<p class="brak-wynikow-oferty" hidden>Brak lokali spełniających wybrane kryteria.</p>
	</div>

	@if($tresci)
		@include($tresci)
	@endif

	<x-sekcje.kafle tag="section" class="oferta-uzupelniajaca" aria-label="Dodatkowe usługi" id="finansowanie" />

	<x-sekcje.kontakt :strona="$inwestycja->name" :investment-id="$inwestycja->id" />
@endsection

@push('scripts')
	<script src="{{ zasob('js/karuzele.js') }}"></script>
	<script src="{{ zasob('js/mapa.js') }}"></script>
	<script src="{{ zasob('js/formularz.js') }}"></script>
@endpush
