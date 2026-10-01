@extends('layouts.page')

@section('meta_title', $page->title)
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset

@section('content')
	<!-- ============ NAGŁÓWEK PODSTRONY ============ -->
	<x-hero-podstrony class="hero-wykonczenie" :sciezka="['Wykończenie pod klucz' => null]" zdjecie="wyk-hero" alt="Wykończone pod klucz wnętrze mieszkania">
		<x-etykieta>WYKOŃCZENIE POD KLUCZ</x-etykieta>
		<h1 class="tytul-sekcji rozjasnia-sie">Wprowadź się<br><span class="akcent">na gotowe mieszkanie</span></h1>

		<div class="wstep pojawia-sie">
			<p>Po zakupie możesz otrzymać gotowe do zamieszkania wnętrze - bez samodzielnego koordynowania ekip, materiałów i harmonogramów. My zajmiemy się wszystkim, bez stresu.</p>
			<p>Nasze doświadczenie i sprawdzony proces pozwalają przeprowadzić wykończenie sprawnie, bezpiecznie i zgodnie z Twoimi oczekiwaniami</p>
		</div>

		<div class="para-przyciskow pojawia-sie opoznienie-1">
			<a href="#kontakt" class="przycisk-pigulka pelny">
				ZAPYTAJ O OFERTĘ
			</a>
			<x-przycisk-pigulka href="#realizacje" class="na-tle">SPRAWDŹ REALIZACJE</x-przycisk-pigulka>
		</div>
	</x-hero-podstrony>

	<!-- ============ KORZYŚCI ============ -->
	<section class="korzysci-sekcja">
		<x-etykieta class="pojawia-sie">KORZYŚCI</x-etykieta>
		<h2 class="tytul-sekcji jasny rozjasnia-sie">Dlaczego warto wybrać<br><span class="akcent">wykończenie pod klucz?</span></h2>
		<span class="ramka-korzysci" aria-hidden="true"></span>

		<ul class="korzysci-lista">
			<li class="kafel-korzysci pojawia-sie ">
				<img src="{{ asset('img/wyk-ikona-czas.svg') }}" width="79" height="79" alt="">
				<h2>Mniej decyzji<br>organizacyjnych</h2>
				<p>Nie musisz samodzielnie<br>koordynować ekip, terminów<br>i dostaw materiału</p>
			</li>
			<li class="kafel-korzysci pojawia-sie opoznienie-1">
				<img src="{{ asset('img/wyk-ikona-wykonczenie.svg') }}" width="79" height="79" alt="">
				<h2>Spójna koncepcja<br>wnętrza</h2>
				<p>Projekt, materiały i wykonanie<br>tworzą jedną przemyślaną<br>i harmonijną całość</p>
			</li>
			<li class="kafel-korzysci pojawia-sie opoznienie-2">
				<img src="{{ asset('img/wyk-ikona-proces.svg') }}" width="79" height="79" alt="">
				<h2>Przejrzysty proces</h2>
				<p>Wiesz, co obejmuje usługa,<br>jakie są etapy i czego możesz<br>się spodziewać</p>
			</li>
			<li class="kafel-korzysci pojawia-sie opoznienie-3">
				<img src="{{ asset('img/wyk-ikona-klucz.svg') }}" width="79" height="79" alt="">
				<h2>Gotowość<br>do zamieszkania</h2>
				<p>Odbierasz mieszkanie<br>przygotowane do codziennego<br>użytkowania</p>
			</li>
		</ul>
	</section>

	<!-- ============ PARTNER WYKOŃCZENIA ============ -->
	<x-sekcje.zdjecie-tekst zdjecie="wyk-partner" alt="Ekipa wykończeniowa przy pracy">
		<x-etykieta>PARTNER WYKOŃCZENIA</x-etykieta>
		<h2 class="tytul-sekcji rozjasnia-sie">Współpracujemy<br><span class="akcent">z firmą Complex</span></h2>
		<p>Współpracujemy z doświadczonym partnerem, który wspiera naszych Klientów w zakresie wykończenia mieszkań pod klucz. Dzięki temu cały proces przebiega sprawniej - od pierwszej rozmowy po gotowe, komfortowe wnętrze</p>

		<p class="zapraszamy">Zapraszamy do kontaktu:</p>

		<ul class="kontakt-partnera">
			<li>
				<span class="ramka"><img src="{{ asset('img/wyk-ikona-telefon.svg') }}" width="44" height="44" alt=""></span>
				<span class="tresc">Telefon<br><a href="tel:+48602311992">+48 602 311 992</a></span>
			</li>
			<li>
				<span class="ramka"><img src="{{ asset('img/wyk-ikona-mail.svg') }}" width="44" height="44" alt=""></span>
				<span class="tresc">Adres e-mail<br><a href="mailto:complex.wykonczenia@gmail.com">complex.wykonczenia@gmail.com</a></span>
			</li>
		</ul>
	</x-sekcje.zdjecie-tekst>

	<!-- ============ GALERIA REALIZACJI ============ -->
	<section class="galeria-realizacji" id="realizacje">

		<div class="naglowek-galerii pojawia-sie">
			<x-etykieta class="na-srodku">GALERIA</x-etykieta>
			<h2 class="tytul-sekcji jasny na-srodku rozjasnia-sie">Sprawdź <span class="akcent">realizacje</span></h2>
		</div>

		<div class="tasma-realizacji pojawia-sie">
			<picture>
				<source type="image/webp" srcset="{{ asset('img/wyk-galeria-3.webp') }}">
				<img src="{{ asset('img/wyk-galeria-3.jpg') }}" width="905" height="552" alt="Realizacja wykończenia - salon" loading="lazy">
			</picture>
			<picture>
				<source type="image/webp" srcset="{{ asset('img/wyk-galeria-1.webp') }}">
				<img src="{{ asset('img/wyk-galeria-1.jpg') }}" width="994" height="606" alt="Realizacja wykończenia - kuchnia z salonem" loading="lazy">
			</picture>
			<picture>
				<source type="image/webp" srcset="{{ asset('img/wyk-galeria-2.webp') }}">
				<img src="{{ asset('img/wyk-galeria-2.jpg') }}" width="905" height="552" alt="Realizacja wykończenia - sypialnia" loading="lazy">
			</picture>
		</div>

		<div class="nawigacja-realizacji pojawia-sie opoznienie-2">
			<button type="button" class="realizacje-poprzednia" aria-label="Poprzednia realizacja">
				<img src="{{ asset('img/strzalka-poprzednia-duza.svg') }}" width="40" height="40" alt="">
			</button>
			<button type="button" class="realizacje-nastepna" aria-label="Następna realizacja">
				<img src="{{ asset('img/strzalka-nastepna-duza.svg') }}" width="39" height="40" alt="">
			</button>
		</div>

	</section>

	<x-sekcje.kontakt />
@endsection

@push('scripts')
	<script src="{{ asset('js/karuzele.js') }}"></script>
	<script src="{{ asset('js/formularz.js') }}"></script>
@endpush
