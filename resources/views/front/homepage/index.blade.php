@extends('layouts.homepage')

@section('content')
@php $slajd = $slajdy[0]; @endphp

	<!-- ============ HERO ============ -->
	<section class="hero" id="hero">

		<div class="hero-tlo">
			<picture>
				<source type="image/webp" srcset="{{ $slajd['srcsetWebp'] }}" sizes="100vw">
				<source type="image/jpeg" srcset="{{ $slajd['srcsetJpg'] }}" sizes="100vw">
				<img src="{{ $slajd['zdjecieJpg'] }}" width="{{ $slajd['szerokosc'] }}" height="{{ $slajd['wysokosc'] }}" alt="{{ $slajd['alt'] }}" fetchpriority="high">
			</picture>
		</div>

		<div class="hero-wnetrze">

			<div class="hero-tresc pojawia-sie">
				<p class="hero-lokalizacja">
					<svg viewBox="0 0 16 25" aria-hidden="true"><path d="M10.0806 0L15.7895 0L5.7089 25H0L10.0806 0Z" fill="#DC5C0C"/></svg>
					<span class="tekst-lokalizacji">{{ $slajd['lokalizacja'] }}</span>
				</p>
				<h1 class="hero-naglowek">{{ $slajd['tytul'] }}</h1>
				<div class="hero-kreska"></div>
			</div>

			<div class="hero-dol">
				<!-- przełączanie slajdów; liczba slajdów czytana ze slidera w js/slider.js -->
				<div class="hero-nawigacja">
					<button type="button" class="slajd-poprzedni" aria-label="Poprzednia inwestycja">
						<svg viewBox="0 0 24 24" aria-hidden="true" fill="none"><path d="M11.9996 1.5L1.49966 12L11.9996 22.5M1.49966 12L22.4996 12" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
					<span class="hero-licznik"><span class="numer-slajdu">1</span> / <span class="ile-slajdow">{{ count($slajdy) }}</span></span>
					<button type="button" class="slajd-nastepny" aria-label="Następna inwestycja">
						<svg viewBox="0 0 24 24" aria-hidden="true" fill="none"><path d="M12 22.5L22.5 12L12 1.50004M22.5 12L1.5 12" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
				</div>

				<a href="{{ $slajd['link'] }}" class="hero-przycisk"@if($slajd['cel']) target="{{ $slajd['cel'] }}"@endif>
					{{ $slajd['przycisk'] }}
					<x-ikona.strzalka />
				</a>
			</div>

		</div>

		<!-- pionowy pasek przy prawej krawędzi -->
		<x-pasek-boczny />

	</section>

	<!-- ============ WYSZUKIWARKA ============ -->
	<!-- Listy opcji są na razie przykładowe - do podpięcia pod bazę inwestycji. -->
	<section class="wyszukiwarka pojawia-sie" aria-label="Wyszukiwarka mieszkań">
		<form class="pasek-wyszukiwarki" action="{{ route('menu.show', ['uri' => 'wyszukiwarka']) }}" method="get">

			<button class="filtr" type="button" data-bs-toggle="dropdown" data-pole="inwestycja" aria-expanded="false">
				<span class="etykieta">Inwestycje</span>
				<span class="wartosc">Wszystkie inwestycje</span>
				<x-ikona.daszek />
			</button>
			<ul class="dropdown-menu lista-filtru">
				<li><button class="dropdown-item wybrany" type="button">Wszystkie inwestycje</button></li>
				<li><button class="dropdown-item" type="button">Dom Hygge Twin</button></li>
				<li><button class="dropdown-item" type="button">Konstancin Riverside House</button></li>
				<li><button class="dropdown-item" type="button">Lake Village</button></li>
				<li><button class="dropdown-item" type="button">Zielona Polana</button></li>
			</ul>

			<button class="filtr" type="button" data-bs-toggle="dropdown" data-pole="pokoje" aria-expanded="false">
				<span class="etykieta">Liczba pokoi</span>
				<span class="wartosc">Dowolna</span>
				<x-ikona.daszek />
			</button>
			<ul class="dropdown-menu lista-filtru">
				<li><button class="dropdown-item wybrany" type="button">Dowolna</button></li>
				<li><button class="dropdown-item" type="button">1</button></li>
				<li><button class="dropdown-item" type="button">2</button></li>
				<li><button class="dropdown-item" type="button">3</button></li>
				<li><button class="dropdown-item" type="button">4 i więcej</button></li>
			</ul>

			<button class="filtr" type="button" data-bs-toggle="dropdown" data-pole="powierzchnia" aria-expanded="false">
				<span class="etykieta">Powierzchnia</span>
				<span class="wartosc">Dowolna</span>
				<x-ikona.daszek />
			</button>
			<ul class="dropdown-menu lista-filtru">
				<li><button class="dropdown-item wybrany" type="button">Dowolna</button></li>
				<li><button class="dropdown-item" type="button">do 40 m²</button></li>
				<li><button class="dropdown-item" type="button">40 - 60 m²</button></li>
				<li><button class="dropdown-item" type="button">60 - 90 m²</button></li>
				<li><button class="dropdown-item" type="button">powyżej 90 m²</button></li>
			</ul>

			<button class="filtr" type="button" data-bs-toggle="dropdown" data-pole="pietro" aria-expanded="false">
				<span class="etykieta">Piętro</span>
				<span class="wartosc">Dowolne</span>
				<x-ikona.daszek />
			</button>
			<ul class="dropdown-menu lista-filtru">
				<li><button class="dropdown-item wybrany" type="button">Dowolne</button></li>
				<li><button class="dropdown-item" type="button">Parter</button></li>
				<li><button class="dropdown-item" type="button">1</button></li>
				<li><button class="dropdown-item" type="button">2</button></li>
				<li><button class="dropdown-item" type="button">3 i wyżej</button></li>
			</ul>

			<button class="filtr" type="button" data-bs-toggle="dropdown" data-pole="cena" aria-expanded="false">
				<span class="etykieta">Cena</span>
				<span class="wartosc">Dowolna</span>
				<x-ikona.daszek />
			</button>
			<ul class="dropdown-menu lista-filtru">
				<li><button class="dropdown-item wybrany" type="button">Dowolna</button></li>
				<li><button class="dropdown-item" type="button">do 600 000 zł</button></li>
				<li><button class="dropdown-item" type="button">600 000 - 900 000 zł</button></li>
				<li><button class="dropdown-item" type="button">900 000 - 1 200 000 zł</button></li>
				<li><button class="dropdown-item" type="button">powyżej 1 200 000 zł</button></li>
			</ul>

			<button class="przycisk-szukaj" type="submit" aria-label="Szukaj mieszkań">
				<svg viewBox="0 0 35 35" aria-hidden="true" fill="none"><path d="M33.538 33.5379L22.846 22.8459M1.46196 13.9359C1.46196 15.574 1.78461 17.1961 2.41149 18.7095C3.03837 20.2229 3.9572 21.598 5.11552 22.7563C6.27384 23.9147 7.64897 24.8335 9.16239 25.4604C10.6758 26.0872 12.2979 26.4099 13.936 26.4099C15.5741 26.4099 17.1962 26.0872 18.7096 25.4604C20.223 24.8335 21.5981 23.9147 22.7565 22.7563C23.9148 21.598 24.8336 20.2229 25.4605 18.7095C26.0874 17.1961 26.41 15.574 26.41 13.9359C26.41 12.2978 26.0874 10.6757 25.4605 9.16227C24.8336 7.64885 23.9148 6.27372 22.7565 5.1154C21.5981 3.95708 20.223 3.03825 18.7096 2.41137C17.1962 1.78449 15.5741 1.46184 13.936 1.46184C12.2979 1.46184 10.6758 1.78449 9.16239 2.41137C7.64897 3.03825 6.27384 3.95708 5.11552 5.1154C3.9572 6.27372 3.03837 7.64885 2.41149 9.16227C1.78461 10.6757 1.46196 12.2978 1.46196 13.9359Z" stroke="white" stroke-width="2.9236" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</button>

		</form>
	</section>

	<!-- ============ INWESTYCJE W SPRZEDAŻY ============ -->
	<section class="inwestycje">
		<div class="uklad">

			<div class="inwestycje-opis pojawia-sie">
				<x-etykieta>INWESTYCJE</x-etykieta>
				<div class="inwestycje-tytul-wiersz">
					<h2 class="tytul-sekcji jasny rozjasnia-sie">Dom Hygge Twin</h2>
					<button type="button" class="inwestycje-przelacznik-szczegolow" aria-expanded="false" aria-controls="szczegolyInwestycji" aria-label="Pokaż szczegóły inwestycji"></button>
				</div>
				<div class="inwestycje-szczegoly-panel" id="szczegolyInwestycji">
					<p class="inwestycje-podtytul">Mieszkania na sprzedaż w stylu Hygge</p>

				<ul class="parametry" id="parametryInwestycji">
					<li>
						<img src="{{ asset('img/ikona-lokalizacja.svg') }}" width="34" height="34" alt="">
						<span><span class="nazwa">WARSZAWA, MOKOTÓW</span><span class="wartosc">ul. Bobrowiecka 4B</span></span>
					</li>
					<li>
						<img src="{{ asset('img/ikona-kalendarz.svg') }}" width="34" height="34" alt="">
						<span><span class="nazwa">TERMIN ODDANIA:</span><span class="wartosc">IV kwartał 2025</span></span>
					</li>
					<li>
						<img src="{{ asset('img/ikona-metraz.svg') }}" width="34" height="34" alt="">
						<span><span class="nazwa">ZAKRES METRAŻU:</span><span class="wartosc">27 - 90,5 m²</span></span>
					</li>
					<li>
						<img src="{{ asset('img/ikona-cena.svg') }}" width="34" height="34" alt="">
						<span><span class="nazwa">CENY OD</span><span class="wartosc">805.516,00 zł</span></span>
					</li>
				</ul>
				</div>

				<div class="inwestycje-akcje">
					<x-przycisk-pigulka href="{{ route('menu.show', ['uri' => 'inwestycje']) }}" class="na-tle inwestycje-przycisk">ZOBACZ INWESTYCJĘ</x-przycisk-pigulka>
				</div>
			</div>

			<div class="inwestycje-galeria pojawia-sie opoznienie-1">
				<div class="galeria-glowna">
					<picture data-tytul="Dom Hygge Twin"
						data-podtytul="Mieszkania na sprzedaż w stylu Hygge"
						data-miasto="WARSZAWA, MOKOTÓW" data-adres="ul. Bobrowiecka 4B"
						data-pasek="ul. Bobrowiecka 4B, Warszawa" data-link="{{ route('menu.show', ['uri' => 'inwestycje']) }}">
						<source type="image/webp" srcset="{{ asset('img/galeria-hygge.webp') }}">
						<img src="{{ asset('img/galeria-hygge.jpg') }}" width="1120" height="616" alt="Dom Hygge Twin - wizualizacja" loading="lazy">
					</picture>
					<span class="plakietka-status">W SPRZEDAŻY</span>
					<p class="pasek-adresu">
						<img src="{{ asset('img/ikona-lokalizacja.svg') }}" width="25" height="25" alt="">
						<span class="tekst-adresu">ul. Bobrowiecka 4B, Warszawa</span>
					</p>
				</div>

				<div class="galeria-miniatury">
					<picture data-tytul="Konstancin Riverside House"
						data-podtytul="Domy w Konstancinie-Jeziornej"
						data-miasto="CHYLICE" data-adres="Konstancin-Jeziorna"
						data-pasek="Chylice, Konstancin-Jeziorna" data-link="{{ route('menu.show', ['uri' => 'inwestycje']) }}">
						<source type="image/webp" srcset="{{ asset('img/inwestycja-2.webp') }}">
						<img src="{{ asset('img/inwestycja-2.jpg') }}" width="469" height="406" alt="Konstancin Riverside House" loading="lazy">
					</picture>
					<picture data-tytul="Segmenty Lake Village"
						data-podtytul="Segmenty w Zalesiu Górnym"
						data-miasto="ZALESIE GÓRNE" data-adres="gm. Piaseczno"
						data-pasek="Zalesie Górne, gm. Piaseczno" data-link="{{ route('menu.show', ['uri' => 'inwestycje']) }}">
						<source type="image/webp" srcset="{{ asset('img/inwestycja-3.webp') }}">
						<img src="{{ asset('img/inwestycja-3.jpg') }}" width="469" height="406" alt="Lake Village" loading="lazy">
					</picture>
					<picture data-tytul="Zespół willowy Zielona Polana"
						data-podtytul="Domy w Nowej Woli"
						data-miasto="NOWA WOLA" data-adres="gm. Lesznowola"
						data-pasek="Nowa Wola, gm. Lesznowola" data-link="{{ route('menu.show', ['uri' => 'inwestycje']) }}">
						<source type="image/webp" srcset="{{ asset('img/lista-zielona-polana.webp') }}">
						<img src="{{ asset('img/lista-zielona-polana.jpg') }}" width="469" height="406" alt="Zielona Polana" loading="lazy">
					</picture>
				</div>

				<div class="galeria-nawigacja">
					<button type="button" class="galeria-poprzednia" aria-label="Poprzednia inwestycja">
						<img src="{{ asset('img/strzalka-poprzednia-duza.svg') }}" width="40" height="40" alt="">
					</button>
					<button type="button" class="galeria-nastepna" aria-label="Następna inwestycja">
						<img src="{{ asset('img/strzalka-nastepna-duza.svg') }}" width="39" height="40" alt="">
					</button>
				</div>
			</div>

		</div>
	</section>

	<!-- ============ O NAS ============ -->
	<section class="o-nas sekcja-karta">

		<div class="o-nas-zdjecia pojawia-sie">
			<picture>
				<source type="image/webp" srcset="{{ asset('img/o-nas-duze.webp') }}">
				<img class="duze" src="{{ asset('img/o-nas-duze.jpg') }}" width="540" height="561" alt="Inwestycja Dynamic Development" loading="lazy">
			</picture>
			<picture>
				<source type="image/webp" srcset="{{ asset('img/o-nas-male.webp') }}">
				<img class="male" src="{{ asset('img/o-nas-male.jpg') }}" width="355" height="458" alt="Wnętrze mieszkania" loading="lazy">
			</picture>
		</div>

		<div class="o-nas-tresc pojawia-sie opoznienie-1">
			<x-etykieta>O NAS</x-etykieta>
			<h2 class="tytul-sekcji rozjasnia-sie">Kreujemy nowoczesną<br><span class="akcent">rzeczywistość</span></h2>

			<div class="wciecie">
				<p>Stawiamy na rozwiązania niekonwencjonalne, innowacyjne i unikalne. Dzięki temu projekty naszych mieszkań i domów spełniają nie tylko współczesne standardy, ale także stanowią odpowiedź na potrzeby przyszłych właścicieli. Rzeczywistość wcale nie musi być nudna i ponura, a nasze nieruchomości są na to najlepszym dowodem. Kreatywność i fantazja to wartości, które mają ogromny potencjał na przyszłość. Właśnie dlatego realizując kolejne projekty, wspomniane wartości stanowią nasze motto, a także są podstawą ideologii, którą się kierujemy. Budujemy komfortowe mieszkania, domy, a także tworzymy niebanalne przestrzenie do rekreacji i wypoczynku.</p>
				<x-przycisk-pigulka href="{{ route('menu.show', ['uri' => 'poznaj-nas']) }}" class="na-tle">WIĘCEJ O NAS</x-przycisk-pigulka>
			</div>
		</div>

	</section>

	<!-- ============ LICZBY ============ -->
	<section class="liczby" aria-label="Dynamic Development w liczbach">
		<div class="liczby-tlo">
			<picture>
				<source type="image/webp" srcset="{{ asset('img/tlo-liczby.webp') }}">
				<img src="{{ asset('img/tlo-liczby.jpg') }}" width="1920" height="503" alt="" loading="lazy">
			</picture>
		</div>

		<ul class="liczby-lista">
			<li class="liczba pojawia-sie">
				<p class="wartosc"><span class="duza-liczba">20+</span> <span class="jednostka">LAT</span></p>
				<span class="opis">DOŚWIADCZENIA W BRANŻY</span>
				<img class="ozdobnik" src="{{ asset('img/ozdoba-liczby.svg') }}" width="93" height="112" alt="">
			</li>
			<li class="liczba pojawia-sie opoznienie-1">
				<p class="wartosc"><span class="duza-liczba">1000</span> <span class="jednostka">LOKALI</span></p>
				<span class="opis">MIESZKALNYCH I UŻYTKOWYCH</span>
				<img class="ozdobnik" src="{{ asset('img/ozdoba-liczby.svg') }}" width="93" height="112" alt="">
			</li>
			<li class="liczba pojawia-sie opoznienie-2">
				<p class="wartosc"><span class="duza-liczba">40</span> <span class="jednostka">TYS.</span></p>
				<span class="opis">METRÓW KWADRATOWYCH</span>
				<img class="ozdobnik" src="{{ asset('img/ozdoba-liczby.svg') }}" width="93" height="112" alt="">
			</li>
		</ul>
	</section>

	<!-- ============ MAPA INWESTYCJI ============ -->
	<section class="mapa-sekcja sekcja-karta">

		<div class="mapa-gora pojawia-sie">
			<div>
				<x-etykieta>MAPA INWESTYCJI</x-etykieta>
				<h2 class="tytul-sekcji rozjasnia-sie">Dobry adres<br><span class="akcent">dla lepszego życia</span></h2>
				<div class="mapa-kreska"></div>
			</div>

			<div class="filtry-mapy">
				<label class="status-sprzedaz">
					<input type="checkbox" data-status="sprzedaz" checked>
					<span class="znacznik"><img src="{{ asset('img/ikona-ptaszek.svg') }}" width="20" height="20" alt=""></span>
					W sprzedaży
				</label>
				<label class="status-zrealizowane">
					<input type="checkbox" data-status="zrealizowane" checked>
					<span class="znacznik"><img src="{{ asset('img/ikona-ptaszek.svg') }}" width="20" height="20" alt=""></span>
					Zrealizowane
				</label>
				<label class="status-realizacja">
					<input type="checkbox" data-status="realizacja">
					<span class="znacznik"><img src="{{ asset('img/ikona-ptaszek.svg') }}" width="20" height="20" alt=""></span>
					W realizacji
				</label>
			</div>
		</div>

		<div class="mapa-plansza pojawia-sie">
			<picture>
				<source type="image/webp" srcset="{{ asset('img/mapa.webp') }}">
				<img src="{{ asset('img/mapa.jpg') }}" width="1920" height="750" alt="Mapa inwestycji Dynamic Development" loading="lazy">
			</picture>

			<!-- Współrzędne ma tylko pinezka: "left"/"top" to jej czubek, czyli sam punkt
			     na mapie. Procenty policzone ze współrzędnych z Figmy (1920 x 750).
			     Dymki nie mają własnych współrzędnych - js/mapa.js stawia je obok pinezki
			     i na jej wysokości.
			     data-strona="lewo" mówi tylko, po której stronie pinezki stoi dymek. -->
			<button class="pinezka" type="button" data-inwestycja="hygge" data-status="sprzedaz" style="left:52.4%;top:42.3%" aria-label="Dom Hygge Twin">
				<img src="{{ asset('img/pinezka-mapa.svg') }}" width="55" height="69" alt="">
			</button>
			<button class="pinezka" type="button" data-inwestycja="lake" data-status="zrealizowane" style="left:44.6%;top:78.3%" aria-label="Lake Village">
				<img src="{{ asset('img/pinezka-mapa.svg') }}" width="55" height="69" alt="">
			</button>
			<button class="pinezka" type="button" data-inwestycja="polana" data-status="zrealizowane" style="left:45.5%;top:81.3%" aria-label="Zielona Polana">
				<img src="{{ asset('img/pinezka-mapa.svg') }}" width="55" height="69" alt="">
			</button>
			<button class="pinezka" type="button" data-inwestycja="konstancin" data-status="sprzedaz" style="left:59.3%;top:79.9%" aria-label="Konstancin Riverside House">
				<img src="{{ asset('img/pinezka-mapa.svg') }}" width="55" height="69" alt="">
			</button>

			<div class="dymek-mapy" data-dymek="hygge">
				<button class="nazwa" type="button">Dom Hygge Twin</button>
				<div class="szczegoly">
					<p class="wiersz"><img src="{{ asset('img/ikona-pin-maly.svg') }}" width="19" height="19" alt=""> Warszawa, Mokotów</p>
					<p class="wiersz"><img src="{{ asset('img/ikona-dom.svg') }}" width="19" height="19" alt=""> Dostępne mieszkania: <span class="akcent">48</span></p>
					<div class="przyciski">
						<a class="mini-przycisk" href="{{ route('menu.show', ['uri' => 'inwestycje']) }}">Szczegóły inwestycji</a>
						<a class="mini-przycisk obrys" href="https://www.google.com/maps/dir/?api=1&amp;destination=ul.%20Bobrowiecka%204B%2C%20Warszawa" target="_blank" rel="noopener"><img src="{{ asset('img/ikona-auto.svg') }}" width="14" height="14" alt=""> Jak dojechać</a>
					</div>
				</div>
			</div>

			<div class="dymek-mapy" data-dymek="lake" data-strona="lewo" data-odsuniecie="-17">
				<button class="nazwa" type="button">Lake Village</button>
				<div class="szczegoly">
					<p class="wiersz"><img src="{{ asset('img/ikona-pin-maly.svg') }}" width="19" height="19" alt=""> Zalesie Górne</p>
					<p class="wiersz"><img src="{{ asset('img/ikona-dom.svg') }}" width="19" height="19" alt=""> Inwestycja zrealizowana</p>
					<div class="przyciski">
						<a class="mini-przycisk" href="{{ route('menu.show', ['uri' => 'inwestycje']) }}">Szczegóły inwestycji</a>
						<a class="mini-przycisk obrys" href="https://www.google.com/maps/dir/?api=1&amp;destination=Zalesie%20G%C3%B3rne" target="_blank" rel="noopener"><img src="{{ asset('img/ikona-auto.svg') }}" width="14" height="14" alt=""> Jak dojechać</a>
					</div>
				</div>
			</div>

			<div class="dymek-mapy" data-dymek="polana" data-strona="lewo" data-odsuniecie="17">
				<button class="nazwa" type="button">Zielona Polana</button>
				<div class="szczegoly">
					<p class="wiersz"><img src="{{ asset('img/ikona-pin-maly.svg') }}" width="19" height="19" alt=""> Nowa Wola</p>
					<p class="wiersz"><img src="{{ asset('img/ikona-dom.svg') }}" width="19" height="19" alt=""> Inwestycja zrealizowana</p>
					<div class="przyciski">
						<a class="mini-przycisk" href="{{ route('menu.show', ['uri' => 'inwestycje']) }}">Szczegóły inwestycji</a>
						<a class="mini-przycisk obrys" href="https://www.google.com/maps/dir/?api=1&amp;destination=Nowa%20Wola" target="_blank" rel="noopener"><img src="{{ asset('img/ikona-auto.svg') }}" width="14" height="14" alt=""> Jak dojechać</a>
					</div>
				</div>
			</div>

			<div class="dymek-mapy rozwiniety" data-dymek="konstancin">
				<button class="nazwa" type="button">Konstancin Riverside House</button>
				<div class="szczegoly">
					<p class="wiersz"><img src="{{ asset('img/ikona-pin-maly.svg') }}" width="19" height="19" alt=""> Chylice / Konstancin-Jeziorna</p>
					<p class="wiersz"><img src="{{ asset('img/ikona-dom.svg') }}" width="19" height="19" alt=""> Dostępne mieszkania: <span class="akcent">32</span></p>
					<div class="przyciski">
						<a class="mini-przycisk" href="{{ route('menu.show', ['uri' => 'inwestycje']) }}">Szczegóły inwestycji</a>
						<a class="mini-przycisk obrys" href="https://www.google.com/maps/dir/?api=1&amp;destination=Chylice%2C%20Konstancin-Jeziorna" target="_blank" rel="noopener"><img src="{{ asset('img/ikona-auto.svg') }}" width="14" height="14" alt=""> Jak dojechać</a>
					</div>
				</div>
			</div>

			<x-mapa-sterowanie />
		</div>

	</section>

	<!-- ============ DLACZEGO WARTO ============ -->
	<section class="dlaczego-warto">
		<div class="uklad">

			<div class="dlaczego-zdjecie pojawia-sie">
				<picture>
					<source type="image/webp" srcset="{{ asset('img/dlaczego-warto.webp') }}">
					<img src="{{ asset('img/dlaczego-warto.jpg') }}" width="1370" height="836" alt="Osiedle Dynamic Development" loading="lazy">
				</picture>
			</div>

			<div class="dlaczego-tresc pojawia-sie opoznienie-1">
				<x-etykieta>DLACZEGO WARTO?</x-etykieta>
				<h2 class="tytul-sekcji rozjasnia-sie">Z myślą o Twoim<br><span class="akcent">komforcie</span></h2>

				<ul class="zalety">
					<li>
						<img src="{{ asset('img/ikona-teczka.svg') }}" width="70" height="70" alt="">
						<div>
							<h3>20+ lat doświadczenia</h3>
							<p>Rodzinny deweloper z polskim kapitałem i blisko 1000 zrealizowanych lokali</p>
						</div>
					</li>
					<li>
						<img src="{{ asset('img/ikona-rysunek.svg') }}" width="68" height="68" alt="">
						<div>
							<h3>Funkcjonalna architektura</h3>
							<p>Przemyślane układy, jasne przestrzenie i rozwiązania dopasowane do codziennego życia</p>
						</div>
					</li>
					<li>
						<img src="{{ asset('img/ikona-park.svg') }}" width="67" height="67" alt="">
						<div>
							<h3>Komfortowe otoczenie</h3>
							<p>Osiedla projektowane z myślą o rekreacji, wypoczynku<br class="lamanie-desktop"> i dobrze zagospodarowanej przestrzeni.</p>
						</div>
					</li>
				</ul>
			</div>

		</div>
	</section>

	<!-- ============ KAFLE: FINANSOWANIE I WYKOŃCZENIE ============ -->
	<x-sekcje.kafle />

	@if($aktualnosci->isNotEmpty())
	<!-- ============ AKTUALNOŚCI ============ -->
	<section class="aktualnosci">

		<div class="aktualnosci-gora pojawia-sie">
			<div>
				<x-etykieta>AKTUALNOŚCI</x-etykieta>
				<h2 class="tytul-sekcji jasny rozjasnia-sie">Co słychać <span class="akcent">nowego?</span></h2>
			</div>
			<x-przycisk-pigulka href="{{ route('aktualnosci.index') }}">WSZYSTKIE AKTUALNOŚCI</x-przycisk-pigulka>
		</div>

		<div class="aktualnosci-lista">
			<div class="aktualnosci-kadr" id="aktualnosciDomowe">
			<div class="row kolejno">
				@foreach($aktualnosci as $wpis)
					<div class="col-12 col-sm-6 col-md-4">
						<x-karta-aktualnosci :tytul="$wpis->title" :zajawka="$wpis->content_entry" :data="$wpis->dataPublikacji()" :link="$wpis->link()" :kategoria="$wpis->category"
							:obrazek="$wpis->zdjecie('thumb')" :webp="$wpis->zdjecie('thumb', true)" :alt="$wpis->file_alt" />
					</div>
				@endforeach
			</div>
			</div>
			<button type="button" class="aktualnosci-wiecej" aria-expanded="false" aria-controls="aktualnosciDomowe">
				<span aria-hidden="true">/</span> POKAŻ WIĘCEJ
			</button>
		</div>

		<div class="aktualnosci-nawigacja">
			<button type="button" class="aktualnosci-poprzednia" aria-label="Poprzednie aktualności">
				<img src="{{ asset('img/ikona-strzalka-poprzednia.svg') }}" width="20" height="20" alt="">
			</button>
			<button type="button" class="aktualnosci-nastepna" aria-label="Następne aktualności">
				<img src="{{ asset('img/ikona-strzalka-nastepna.svg') }}" width="20" height="20" alt="">
			</button>
		</div>

	</section>
	@endif

	<!-- ============ KONTAKT ============ -->
	<x-sekcje.kontakt strona="Strona główna" />
@endsection

@push('scripts')
	{{-- Slajdy hero z panelu (Slider) - lista dla js/slider.js; pierwszy jest też w HTML wyżej (SEO) --}}
	<script>window.slajdyHero = @json($slajdy);</script>
	<script src="{{ asset('js/slider.js') }}"></script>
	<script src="{{ asset('js/karuzele.js') }}"></script>
	<script src="{{ asset('js/aktualnosci-domowa.js') }}"></script>
	<script src="{{ asset('js/mapa.js') }}"></script>
	<script src="{{ asset('js/formularz.js') }}"></script>
@endpush
