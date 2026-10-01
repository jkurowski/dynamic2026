@extends('layouts.page')

@section('main_class', 'ma-pasek-boczny')
@section('meta_title', $page->title ?? 'Kontakt')
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset

@section('content')
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
			<li class="col-12 col-md-6">
				<div class="karta-biura pojawia-sie ">
					<h3>Warszawa</h3>
					<div class="mapka-biura">
						<picture><source type="image/webp" srcset="{{ asset('img/kod-qr.webp') }}"><img class="kod-qr" src="{{ asset('img/kod-qr.png') }}" width="151" height="149" alt="Kod QR z trasą do biura Warszawa" loading="lazy"></picture>
					</div>
					<ul class="dane-biura-lista">
						<li>
							<img src="{{ asset('img/ikona-adres.svg') }}" width="46" height="46" alt="">
							<span class="tresc">ul. Bobrowiecka 1B/U3<br>00-728 Warszawa</span>
						</li>
						<li>
							<img src="{{ asset('img/ikona-sluchawka.svg') }}" width="46" height="46" alt="">
							<span class="tresc"><strong>Telefon</strong><br>+48 576 786 666</span>
						</li>
						<li>
							<img src="{{ asset('img/ikona-zegar.svg') }}" width="46" height="46" alt="">
							<span class="tresc"><strong>Godziny otwarcia:</strong><br>Poniedziałek - Piątek 9:00-17:00<br>Sobota - po wcześniejszym umówieniu<br>Niedziela – nieczynne</span>
						</li>
					</ul>
					<a class="odnosnik-trasy" href="https://www.google.com/maps/dir/?api=1&amp;destination=ul.%20Bobrowiecka%201B%2FU3%2C%2000-728%20Warszawa" target="_blank" rel="noopener"><strong>Wyznacz trasę</strong></a>
				</div>
			</li>
			<li class="col-12 col-md-6">
				<div class="karta-biura pojawia-sie opoznienie-1">
					<h3>Nowa Wola</h3>
					<div class="mapka-biura">
						<picture><source type="image/webp" srcset="{{ asset('img/kod-qr.webp') }}"><img class="kod-qr" src="{{ asset('img/kod-qr.png') }}" width="151" height="149" alt="Kod QR z trasą do biura Nowa Wola" loading="lazy"></picture>
					</div>
					<ul class="dane-biura-lista">
						<li>
							<img src="{{ asset('img/ikona-adres.svg') }}" width="46" height="46" alt="">
							<span class="tresc">ul. Maciejki 8/2<br>05-515 Nowa Wola</span>
						</li>
						<li>
							<img src="{{ asset('img/ikona-sluchawka.svg') }}" width="46" height="46" alt="">
							<span class="tresc"><strong>Telefon</strong><br>+48 512 379 056</span>
						</li>
						<li>
							<img src="{{ asset('img/ikona-zegar.svg') }}" width="46" height="46" alt="">
							<span class="tresc"><strong>Godziny otwarcia:</strong><br>Poniedziałek - Piątek 9:00-17:00</span>
						</li>
					</ul>
					<a class="odnosnik-trasy" href="https://www.google.com/maps/dir/?api=1&amp;destination=ul.%20Maciejki%208%2F2%2C%2005-515%20Nowa%20Wola" target="_blank" rel="noopener"><strong>Wyznacz trasę</strong></a>
				</div>
			</li>
		</ul>
	</section>

	<!-- ============ MAPA BIUR ============ -->
	<!-- Podpisy biur są widoczne od razu; wspólny sterownik dodaje zoom i przesuwanie. -->
	<section class="mapa-plansza mapa-biur pojawia-sie" aria-label="Mapa z lokalizacjami biur">
		<picture>
			<source type="image/webp" srcset="{{ asset('img/mapa.webp') }}">
			<img src="{{ asset('img/mapa.jpg') }}" width="1920" height="750" alt="Mapa z lokalizacjami biur Dynamic Development" loading="lazy">
		</picture>

		<span class="pinezka" data-inwestycja="warszawa" style="left:52.4%;top:42.3%" aria-hidden="true">
			<img src="{{ asset('img/pinezka-mapa.svg') }}" width="55" height="69" alt="">
		</span>
		<div class="dymek-mapy" data-dymek="warszawa">
			<span class="nazwa">Biuro Sprzedaży w Warszawie</span>
		</div>

		<span class="pinezka" data-inwestycja="nowa-wola" style="left:44.6%;top:78.3%" aria-hidden="true">
			<img src="{{ asset('img/pinezka-mapa.svg') }}" width="55" height="69" alt="">
		</span>
		<div class="dymek-mapy" data-dymek="nowa-wola">
			<span class="nazwa">Biuro Sprzedaży w Nowej Woli</span>
		</div>

		<x-mapa-sterowanie />
	</section>
@endsection

@push('scripts')
	<script src="{{ asset('js/formularz.js') }}"></script>
	<script src="{{ asset('js/mapa.js') }}"></script>
@endpush
