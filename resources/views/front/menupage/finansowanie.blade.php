@extends('layouts.page')

@section('body_class', 'strona-finansowania')
@section('meta_title', $page->title)
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset

@section('content')
	<!-- ============ NAGŁÓWEK PODSTRONY ============ -->
	<x-hero-podstrony class="hero-finansowanie" :sciezka="['Finansowanie' => null]" zdjecie="fin-hero" alt="Doradca finansowy podczas spotkania z klientem">
		<x-etykieta>FINANSOWANIE</x-etykieta>
		<h1 class="tytul-sekcji rozjasnia-sie">Sprawdź swoją ratę,<br><span class="akcent">zanim kupisz</span></h1>
		<p class="wstep pojawia-sie">Pomagamy Ci przejść przez cały proces finansowania<br class="lamanie-desktop"> zakupu mieszkania - od pierwszej rozmowy po podpisanie<br class="lamanie-desktop"> umowy kredytowej.</p>

		<x-przycisk-pigulka href="#kalkulator" class="na-tle pojawia-sie opoznienie-1">SPRAWDŹ SWOJĄ RATĘ</x-przycisk-pigulka>

		<ul class="atuty pojawia-sie opoznienie-2">
		<li>
			<img src="{{ asset('img/fin-ikona-doradca.svg') }}" width="71" height="71" alt="">
			<h2>Wsparcie doradcy</h2>
			<p>Indywidualna pomoc<br class="lamanie-desktop"> na każdym etapie finansowania</p>
		</li>
		<li>
			<img src="{{ asset('img/fin-ikona-kalkulator.svg') }}" width="67" height="67" alt="">
			<h2>Szacunkowa rata</h2>
			<p>Szybka symulacja raty dopasowana do Twoich możliwości</p>
		</li>
		<li>
			<img src="{{ asset('img/fin-ikona-formalnosci.svg') }}" width="67" height="67" alt="">
			<h2>Sprawne formalności</h2>
			<p>Wspieramy w kompletowaniu<br class="lamanie-desktop"> dokumentów i kontaktach<br class="lamanie-desktop"> z bankiem</p>
		</li>
		</ul>
	</x-hero-podstrony>

	<!-- ============ KALKULATOR RATY ============ -->
	<section class="kalkulator-sekcja" id="kalkulator">

		<div class="kalkulator-opis pojawia-sie">
			<x-etykieta>OBLICZ RATĘ</x-etykieta>
			<h2 class="tytul-sekcji rozjasnia-sie">Zapewniamy pomoc<br><span class="akcent">w uzyskaniu kredytu</span></h2>
			<p>Współpracujący z Dynamic Development ekspert kredytowy przedstawi bogaty wybór produktów hipotecznych, odpowie na wszelkie pytania,<br class="lamanie-desktop"> a także doradzi w wyborze właściwego rodzaju oprocentowania. Przeprowadzi on także pełną analizę zdolności kredytowej, by wniosek<br class="lamanie-desktop"> o hipotekę zakończył się korzystnie dla Klienta.</p>
			<x-przycisk-pigulka href="#kontakt" class="na-tle">SKONTAKTUJ SIĘ Z DORADCĄ</x-przycisk-pigulka>
		</div>

		<div class="karta-kalkulatora pojawia-sie opoznienie-1" id="kalkulatorRaty">
			<h2>Kalkulator raty kredytowej</h2>
			<p class="wprowadzenie">Oblicz przybliżoną miesięczną ratę kredytu hipotecznego.</p>

			<div class="suwak">
				<div class="opis">
					<label class="podpis" for="suwakKwoty">Kwota kredytu</label>
					<span class="wartosc wartosc-kwoty">500 000 zł</span>
				</div>
				<input class="tor" type="range" id="suwakKwoty" name="kwota"
				       min="100000" max="1200000" step="10000" value="500000"
				       aria-label="Kwota kredytu w złotych">
			</div>

			<div class="suwak">
				<div class="opis">
					<label class="podpis" for="suwakOkresu">Okres kredytowania</label>
					<span class="wartosc wartosc-okresu">20 lat</span>
				</div>
				<!-- Zakres dobrany tak, żeby uchwyt dla 20 lat stanął tam, gdzie stoi
				     w makiecie: wypełnienie 252px z 698px toru (Rectangle 328). -->
				<input class="tor" type="range" id="suwakOkresu" name="okres"
				       min="5" max="47" step="1" value="20"
				       aria-label="Okres kredytowania w latach">
			</div>

			<p class="oprocentowanie">Oprocentowanie: <span class="akcent">6,7%</span></p>

			<div class="podsumowanie-raty">
				<span class="podpis">Szacunkowa rata:</span>
				<span class="wynik-raty" role="status">0,00 zł</span>
			</div>

			<p class="zastrzezenie">Symulacja ma charakter poglądowy i nie stanowi oferty handlowej w rozumieniu Kodeksu Cywilnego</p>
		</div>

	</section>

	<!-- ============ PARTNER KREDYTOWY ============ -->
	<section class="partner-sekcja sekcja-karta">

		<div class="partner-opis pojawia-sie">
			<x-etykieta>PARTNER KREDYTOWY</x-etykieta>
			<h2 class="tytul-sekcji rozjasnia-sie">Wsparcie<br><span class="akcent">doradcy finansowego</span></h2>
			<ul class="lista-wsparcia">
				<li class="pojawia-sie">
					<img src="{{ asset('img/fin-ikona-analiza.svg') }}" width="46" height="46" alt="">
					<span>analiza zdolności kredytowej</span>
				</li>
				<li class="pojawia-sie opoznienie-1">
					<img src="{{ asset('img/fin-ikona-oferta.svg') }}" width="46" height="46" alt="">
					<span>pomoc w wyborze oferty</span>
				</li>
				<li class="pojawia-sie opoznienie-2">
					<img src="{{ asset('img/fin-ikona-dokumenty.svg') }}" width="46" height="46" alt="">
					<span>wsparcie w kompletowaniu dokumentów</span>
				</li>
				<li class="pojawia-sie opoznienie-3">
					<img src="{{ asset('img/fin-ikona-opieka.svg') }}" width="46" height="46" alt="">
					<span>opieka na każdym etapie procesu</span>
				</li>
			</ul>
		</div>

		<div class="karta-partnera pojawia-sie opoznienie-1">
			<picture>
				<source type="image/webp" srcset="{{ asset('img/fin-logo-partnera.webp') }}">
				<img class="logo-partnera" src="{{ asset('img/fin-logo-partnera.png') }}" width="506" height="134" alt="Notus Finanse - partner kredytowy" loading="lazy">
			</picture>

			<div class="wizytowka">
				<div class="gora">
					<picture>
						<source type="image/webp" srcset="{{ asset('img/fin-doradca.webp') }}">
						<img class="zdjecie" src="{{ asset('img/fin-doradca.jpg') }}" width="199" height="199" alt="Karol Biniek, doradca finansowy" loading="lazy">
					</picture>
					<div>
						<h3>Karol Biniek</h3>
						<p class="stanowisko">Doradca finansowy</p>

						<ul class="dane-doradcy">
							<li>
								<span class="ramka"><img src="{{ asset('img/fin-ikona-telefon.svg') }}" width="31" height="31" alt=""></span>
								<span class="tresc">Telefon<br><a href="tel:+48576786666">+48 576 786 666</a></span>
							</li>
							<li>
								<span class="ramka"><img src="{{ asset('img/fin-ikona-mail.svg') }}" width="31" height="31" alt=""></span>
								<span class="tresc">Adres e-mail<br><a href="mailto:karol.biniek@notusfinanse.pl">karol.biniek@notusfinanse.pl</a></span>
							</li>
						</ul>
					</div>
				</div>

				<div class="stopka-wizytowki">
					<img src="{{ asset('img/fin-ikona-rozmowa.svg') }}" width="55" height="55" alt="">
					<p>Doradca finansowy pomoże umówić spotkanie w dogodnym terminie<br class="lamanie-desktop"> i poprowadzi przez cały proces finansowania</p>
				</div>
			</div>
		</div>

	</section>

	<x-sekcje.kontakt />
@endsection

@push('scripts')
	<script src="{{ asset('js/kalkulator.js') }}"></script>
	<script src="{{ asset('js/formularz.js') }}"></script>
@endpush
