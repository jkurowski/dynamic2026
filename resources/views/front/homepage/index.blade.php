@extends('layouts.homepage')

@section('content')
@php
    // Treść tymczasowa z makiety - do podpięcia pod moduł Aktualności (baza jest pusta).
    $aktualnosci = [
        ['tytul' => 'Lorem ipsum dolor', 'zajawka' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, pellentesque laoreet.', 'data' => '2026-05-20', 'link' => route('aktualnosci.index'), 'kategoria' => 'NOWA INWESTYCJA'],
        ['tytul' => 'Lorem ipsum dolor', 'zajawka' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, pellentesque laoreet.', 'data' => '2026-05-20', 'link' => route('aktualnosci.index'), 'kategoria' => 'DZIENNIK INWESTYCJI'],
        ['tytul' => 'Lorem ipsum dolor', 'zajawka' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, pellentesque laoreet.', 'data' => '2026-05-20', 'link' => route('aktualnosci.index'), 'kategoria' => 'NOWA INWESTYCJA'],
    ];
@endphp

	<!-- ============ HERO ============ -->
	<section class="hero" id="hero">

		<div class="hero-tlo">
			<picture>
				<source type="image/webp" srcset="{{ asset('img/hero-768.webp') }} 768w, {{ asset('img/hero-1280.webp') }} 1280w, {{ asset('img/hero-1920.webp') }} 1920w" sizes="100vw">
				<source type="image/jpeg" srcset="{{ asset('img/hero-768.jpg') }} 768w, {{ asset('img/hero-1280.jpg') }} 1280w, {{ asset('img/hero-1920.jpg') }} 1920w" sizes="100vw">
				<img src="{{ asset('img/hero-1920.jpg') }}" width="1925" height="1155" alt="Wizualizacja inwestycji Dom Hygge Twin" fetchpriority="high">
			</picture>
		</div>

		<div class="hero-wnetrze">

			<div class="hero-tresc pojawia-sie">
				<p class="hero-lokalizacja">
					<svg viewBox="0 0 16 25" aria-hidden="true"><path d="M10.0806 0L15.7895 0L5.7089 25H0L10.0806 0Z" fill="#DC5C0C"/></svg>
					<span class="tekst-lokalizacji">WARSZAWA · MOKOTÓW</span>
				</p>
				<h1 class="hero-naglowek">Dom Hygge Twin</h1>
				<div class="hero-kreska"></div>
			</div>

			<div class="hero-dol">
				<!-- przełączanie slajdów; liczba slajdów czytana ze slidera w js/slider.js -->
				<div class="hero-nawigacja">
					<button type="button" class="slajd-poprzedni" aria-label="Poprzednia inwestycja">
						<svg viewBox="0 0 24 24" aria-hidden="true" fill="none"><path d="M11.9996 1.5L1.49966 12L11.9996 22.5M1.49966 12L22.4996 12" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
					<span class="hero-licznik"><span class="numer-slajdu">1</span> / <span class="ile-slajdow">4</span></span>
					<button type="button" class="slajd-nastepny" aria-label="Następna inwestycja">
						<svg viewBox="0 0 24 24" aria-hidden="true" fill="none"><path d="M12 22.5L22.5 12L12 1.50004M22.5 12L1.5 12" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
				</div>

				<a href="{{ url('inwestycje') }}" class="hero-przycisk">
					Zobacz inwestycję
					<x-ikona.strzalka />
				</a>
			</div>

		</div>

		<!-- pionowy pasek przy prawej krawędzi -->
		<div class="pasek-boczny">
			<a href="https://www.facebook.com/DynamicDevelopmentPolska/" class="ikona-okragla" target="_blank" rel="noopener" aria-label="Facebook">
				<svg viewBox="0 0 35 35" aria-hidden="true"><path d="M20.4168 19.6878H24.0627L25.521 13.8545H20.4168V10.9378C20.4168 9.43574 20.4168 8.02116 23.3335 8.02116H25.521V3.12116C25.0456 3.05845 23.2504 2.91699 21.3545 2.91699C17.3952 2.91699 14.5835 5.33345 14.5835 9.77116V13.8545H10.2085V19.6878H14.5835V32.0837H20.4168V19.6878Z" fill="white"/></svg>
			</a>

			<a href="https://www.instagram.com/dynamicdevelopmentpolska/" class="ikona-okragla" target="_blank" rel="noopener" aria-label="Instagram">
				<svg viewBox="0 0 32 32" aria-hidden="true" fill="none">
					<path d="M22.4 1.59997C26.8159 1.59997 30.3999 5.18395 30.3999 9.59992V22.3998C30.3999 26.8158 26.8159 30.3998 22.4 30.3998H9.60004C5.18408 30.3998 1.6001 26.8158 1.6001 22.3998V9.59992C1.6001 5.18395 5.18408 1.59997 9.60004 1.59997H16H22.4Z" stroke="white" stroke-width="3.11111" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M15.9996 9.60048C19.5355 9.60048 22.3995 12.4645 22.3995 16.0004C22.3995 19.5364 19.5355 22.4004 15.9996 22.4004C12.4636 22.4004 9.5996 19.5364 9.5996 16.0004C9.5996 12.4645 12.4636 9.60048 15.9996 9.60048Z" stroke="white" stroke-width="3.11111" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M23.9998 10.3998C25.3253 10.3998 26.3998 9.32524 26.3998 7.99977C26.3998 6.6743 25.3253 5.59979 23.9998 5.59979C22.6744 5.59979 21.5998 6.6743 21.5998 7.99977C21.5998 9.32524 22.6744 10.3998 23.9998 10.3998Z" fill="white"/>
				</svg>
			</a>

			<!-- Przełącznik motywu. Wygląd 1:1 z projektu, jasna paleta wprost z makiety
			     "STRONA GŁÓWNA - JASNA WERSJA", wybór zapamiętany w localStorage. -->
			<div class="przelacznik-motywu">
				<button type="button" class="ikona-okragla" id="przelacznikMotywu" aria-label="Zmień tryb na jasny">
					<picture>
						<source srcset="{{ asset('img/ikona-dzien-noc.webp') }}" type="image/webp">
						<img src="{{ asset('img/ikona-dzien-noc.png') }}" width="49" height="49" alt="">
					</picture>
				</button>
				<span class="dymek">Tryb nocny/dzienny</span>
			</div>

			<a href="{{ url('wyszukiwarka') }}" class="ikona-okragla" aria-label="Ulubione lokale">
				<svg viewBox="0 0 35 35" aria-hidden="true"><path d="M17.6457 27.0521L17.4998 27.1979L17.3394 27.0521C10.4123 20.7667 5.83317 16.6104 5.83317 12.3958C5.83317 9.47917 8.02067 7.29167 10.9373 7.29167C13.1832 7.29167 15.3707 8.75 16.1436 10.7333H18.8561C19.629 8.75 21.8165 7.29167 24.0623 7.29167C26.979 7.29167 29.1665 9.47917 29.1665 12.3958C29.1665 16.6104 24.5873 20.7667 17.6457 27.0521ZM24.0623 4.375C21.5248 4.375 19.0894 5.55625 17.4998 7.40833C15.9103 5.55625 13.4748 4.375 10.9373 4.375C6.44567 4.375 2.9165 7.88958 2.9165 12.3958C2.9165 17.8938 7.87484 22.4 15.3853 29.2104L17.4998 31.1354L19.6144 29.2104C27.1248 22.4 32.0832 17.8938 32.0832 12.3958C32.0832 7.88958 28.554 4.375 24.0623 4.375Z" fill="white"/></svg>
				<span class="licznik-ulubionych">0</span>
			</a>
		</div>

	</section>

	<!-- ============ WYSZUKIWARKA ============ -->
	<!-- Listy opcji są na razie przykładowe - do podpięcia pod bazę inwestycji. -->
	<section class="wyszukiwarka pojawia-sie" aria-label="Wyszukiwarka mieszkań">
		<form class="pasek-wyszukiwarki" action="{{ url('wyszukiwarka') }}" method="get">

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
					<x-przycisk-pigulka href="{{ url('inwestycje') }}" class="na-tle inwestycje-przycisk">ZOBACZ INWESTYCJĘ</x-przycisk-pigulka>
				</div>
			</div>

			<div class="inwestycje-galeria pojawia-sie opoznienie-1">
				<div class="galeria-glowna">
					<picture data-tytul="Dom Hygge Twin"
						data-podtytul="Mieszkania na sprzedaż w stylu Hygge"
						data-miasto="WARSZAWA, MOKOTÓW" data-adres="ul. Bobrowiecka 4B"
						data-pasek="ul. Bobrowiecka 4B, Warszawa" data-link="{{ url('inwestycje') }}">
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
						data-pasek="Chylice, Konstancin-Jeziorna" data-link="{{ url('inwestycje') }}">
						<source type="image/webp" srcset="{{ asset('img/inwestycja-2.webp') }}">
						<img src="{{ asset('img/inwestycja-2.jpg') }}" width="469" height="406" alt="Konstancin Riverside House" loading="lazy">
					</picture>
					<picture data-tytul="Segmenty Lake Village"
						data-podtytul="Segmenty w Zalesiu Górnym"
						data-miasto="ZALESIE GÓRNE" data-adres="gm. Piaseczno"
						data-pasek="Zalesie Górne, gm. Piaseczno" data-link="{{ url('inwestycje') }}">
						<source type="image/webp" srcset="{{ asset('img/inwestycja-3.webp') }}">
						<img src="{{ asset('img/inwestycja-3.jpg') }}" width="469" height="406" alt="Lake Village" loading="lazy">
					</picture>
					<picture data-tytul="Zespół willowy Zielona Polana"
						data-podtytul="Domy w Nowej Woli"
						data-miasto="NOWA WOLA" data-adres="gm. Lesznowola"
						data-pasek="Nowa Wola, gm. Lesznowola" data-link="{{ url('inwestycje') }}">
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
				<x-przycisk-pigulka href="{{ url('poznaj-nas') }}" class="na-tle">WIĘCEJ O NAS</x-przycisk-pigulka>
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
						<a class="mini-przycisk" href="{{ url('inwestycje') }}">Szczegóły inwestycji</a>
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
						<a class="mini-przycisk" href="{{ url('inwestycje') }}">Szczegóły inwestycji</a>
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
						<a class="mini-przycisk" href="{{ url('inwestycje') }}">Szczegóły inwestycji</a>
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
						<a class="mini-przycisk" href="{{ url('inwestycje') }}">Szczegóły inwestycji</a>
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
						<x-karta-aktualnosci :tytul="$wpis['tytul']" :zajawka="$wpis['zajawka']" :data="$wpis['data']" :link="$wpis['link']" :kategoria="$wpis['kategoria']"
							:obrazek="asset('img/aktualnosc.jpg')" :webp="asset('img/aktualnosc.webp')" />
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

	<!-- ============ KONTAKT ============ -->
	<x-sekcje.kontakt />
@endsection

@push('scripts')
	<script src="{{ asset('js/slider.js') }}"></script>
	<script src="{{ asset('js/karuzele.js') }}"></script>
	<script src="{{ asset('js/aktualnosci-domowa.js') }}"></script>
	<script src="{{ asset('js/mapa.js') }}"></script>
	<script src="{{ asset('js/formularz.js') }}"></script>
@endpush
