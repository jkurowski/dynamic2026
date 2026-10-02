@extends('layouts.page')

@section('main_class', 'ma-pasek-boczny')
@section('meta_title', $page->title ?? 'Kontakt')
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset

@section('content')
@php $biura = \App\Models\City::biura(); @endphp
	<!-- ============ NAGŁÓWEK PODSTRONY + FORMULARZ ============ -->
	<section class="naglowek-strony">
		<x-okruszki :sciezka="['Kontakt' => null]" />

		<div class="kontakt-gora">
			<div class="kolumna-tekst pojawia-sie">
				<x-etykieta>KONTAKT</x-etykieta>
				<h1 class="tytul-sekcji jasny rozjasnia-sie">Skontaktuj się <span class="akcent">z nami</span></h1>

				<div class="kontakt-opis">
					<p>Wiemy, że wybór pierwszego mieszkania lub domu w Warszawie lub okolicy nie jest najłatwiejszym zadaniem. Właśnie dlatego wspieramy naszych Klientów od początku procesu, czyli wyboru inwestycji, aż do jego zakończenia, czyli sfinalizowania transakcji.</p>
					<p>Jeśli masz jakiekolwiek pytania odnośnie naszej działalności, gotowych inwestycji, a także sposobów finansowania i mieszkań pod klucz, to możesz się z nami skontaktować!</p>
				</div>

				<ul class="skrot-kontaktu">
				<li>
					<span class="ramka-ikony"><img src="{{ asset('img/ikona-sluchawka.svg') }}" width="54" height="54" alt=""></span>
					<span class="tresc">Telefon<br><a href="tel:+48576786666">+48 576 786 666</a></span>
				</li>
				<li>
					<span class="ramka-ikony"><img src="{{ asset('img/ikona-koperta.svg') }}" width="54" height="54" alt=""></span>
					<span class="tresc">Adres e-mail<br><a href="mailto:biuro@dynamicdevelopment.pl">biuro@dynamicdevelopment.pl</a></span>
				</li>
				<li>
					<span class="ramka-ikony"><img src="{{ asset('img/ikona-zegar.svg') }}" width="54" height="54" alt=""></span>
					<span class="tresc">Godziny otwarcia<br><strong>poniedziałek-piątek 9:00-17:00</strong></span>
				</li>
				</ul>
			</div>

			<x-formularz-kontaktowy :strona="$page->title ?? 'Kontakt'" />
		</div>
	</section>

		<x-pasek-boczny />

	<!-- ============ NASZE BIURA ============ -->
	<section class="biura sekcja-karta">
		<x-etykieta class="pojawia-sie">NASZE BIURA</x-etykieta>
		<h2 class="tytul-sekcji jasny rozjasnia-sie">Odwiedź nas w <span class="akcent">dogodnej lokalizacji</span></h2>

		<ul class="biura-lista kolejno row list-unstyled">
			@foreach($biura as $biuro)
			<li class="col-12 col-md-6">
				<div class="karta-biura pojawia-sie {{ $loop->even ? 'opoznienie-1' : '' }}">
					<h3>{{ $biuro->name }}</h3>
					<div class="mapka-biura">
						<picture><source type="image/webp" srcset="{{ $biuro->qr(true) }}"><img class="kod-qr" src="{{ $biuro->qr() }}" width="151" height="149" alt="Kod QR z trasą do biura {{ $biuro->name }}" loading="lazy"></picture>
					</div>
					<ul class="dane-biura-lista">
						<li>
							<img src="{{ asset('img/ikona-adres.svg') }}" width="46" height="46" alt="">
							<span class="tresc">{!! $biuro->address !!}</span>
						</li>
						@if($biuro->phone)
						<li>
							<img src="{{ asset('img/ikona-sluchawka.svg') }}" width="46" height="46" alt="">
							<span class="tresc"><strong>Telefon</strong><br>{!! $biuro->phone !!}</span>
						</li>
						@endif
						@if($biuro->working_hours)
						<li>
							<img src="{{ asset('img/ikona-zegar.svg') }}" width="46" height="46" alt="">
							<span class="tresc"><strong>Godziny otwarcia:</strong><br>{!! $biuro->working_hours !!}</span>
						</li>
						@endif
					</ul>
					@if($biuro->map_link)
					<a class="odnosnik-trasy" href="{{ $biuro->map_link }}" target="_blank" rel="noopener"><strong>Wyznacz trasę</strong></a>
					@endif
				</div>
			</li>
			@endforeach
		</ul>
	</section>

	<!-- ============ MAPA BIUR ============ -->
	<!-- Podpisy biur są widoczne od razu; wspólny sterownik dodaje zoom i przesuwanie. -->
	<section class="mapa-plansza mapa-biur pojawia-sie" aria-label="Mapa z lokalizacjami biur">
		<picture>
			<source type="image/webp" srcset="{{ asset('img/mapa.webp') }}">
			<img src="{{ asset('img/mapa.jpg') }}" width="1920" height="750" alt="Mapa z lokalizacjami biur Dynamic Development" loading="lazy">
		</picture>

		{{-- Pinezki biur z lat/lng (przeliczenie na pozycję na obrazku mapy: config/mapa.php) --}}
		@foreach($biura as $biuro)
			@if($pozycja = $biuro->pozycjaNaMapie())
		<span class="pinezka" data-inwestycja="{{ $biuro->slug }}" style="left:{{ $pozycja['left'] }}%;top:{{ $pozycja['top'] }}%" aria-hidden="true">
			<img src="{{ asset('img/pinezka-mapa.svg') }}" width="55" height="69" alt="">
		</span>
		<div class="dymek-mapy" data-dymek="{{ $biuro->slug }}">
			<span class="nazwa">Biuro Sprzedaży {{ $biuro->name }}</span>
		</div>
			@endif
		@endforeach

		<x-mapa-sterowanie />
	</section>
@endsection

@push('scripts')
	<script src="{{ asset('js/formularz.js') }}"></script>
	<script src="{{ asset('js/mapa.js') }}"></script>
@endpush
