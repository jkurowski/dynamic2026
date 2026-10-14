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
	<!-- Lista inwestycji z bazy (te z ofertą lokali); filtry przechodzą do /wybierz-nieruchomosc jako parametry adresu -->
	<section class="wyszukiwarka pojawia-sie" aria-label="Wyszukiwarka nieruchomości">
		<form class="pasek-wyszukiwarki" action="{{ route('wyszukiwarka') }}" method="get">

			<button class="filtr" type="button" data-bs-toggle="dropdown" data-pole="inwestycja" aria-expanded="false">
				<span class="etykieta">Inwestycje</span>
				<span class="wartosc">Wszystkie inwestycje</span>
				<x-ikona.daszek />
			</button>
			<ul class="dropdown-menu lista-filtru">
				<li><button class="dropdown-item wybrany" type="button">Wszystkie inwestycje</button></li>
				@foreach(\App\Models\Investment::frontu()->filter(fn ($i) => $i->maPodstrone()) as $inwestycjaWyszukiwarki)
					<li><button class="dropdown-item" type="button">{{ $inwestycjaWyszukiwarki->name }}</button></li>
				@endforeach
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
	{{-- Z bazy: pierwsza inwestycja duże zdjęcie, reszta miniatury. Dane wymienianych inwestycji w data-* zdjęć
	     (js/karuzele.js - wpiszOpis), puste parametry (inwestycja bez lokali w panelu) są ukrywane. --}}
	@php
		$inwestycjeGlowna = \App\Models\Investment::frontu()->where('status', 1)->values();
		$pierwszaInwestycja = $inwestycjeGlowna->first();
		$daneInwestycji = fn ($i) => [
			'tytul' => $i->name,
			'podtytul' => $i->entry_content,
			'miasto' => $i->location_label,
			'adres' => $i->address,
			'pasek' => collect([$i->address, \App\Support\FiltryOferty::lokalizacja($i)])->filter()->implode(', '),
			'link' => $i->linkMenu(),
			'zewnetrzny' => $i->linkZewnetrzny() ? 1 : 0,
			'termin' => $i->terminOddania(),
			'metraz' => $i->zakresMetrazu(),
			'cena' => $i->cenaOd(),
		];
	@endphp
	@if($pierwszaInwestycja)
	@php $dane = $daneInwestycji($pierwszaInwestycja); @endphp
	<section class="inwestycje">
		<div class="uklad">

			<div class="inwestycje-opis pojawia-sie">
				<x-etykieta>INWESTYCJE</x-etykieta>
				<div class="inwestycje-tytul-wiersz">
					<h2 class="tytul-sekcji jasny rozjasnia-sie">{{ $dane['tytul'] }}</h2>
					<button type="button" class="inwestycje-przelacznik-szczegolow" aria-expanded="false" aria-controls="szczegolyInwestycji" aria-label="Pokaż szczegóły inwestycji"></button>
				</div>
				<div class="inwestycje-szczegoly-panel" id="szczegolyInwestycji">
					<p class="inwestycje-podtytul">{{ $dane['podtytul'] }}</p>

				<ul class="parametry" id="parametryInwestycji">
					<li>
						<img src="{{ asset('img/ikona-lokalizacja.svg') }}" width="34" height="34" alt="">
						<span><span class="nazwa">{{ $dane['miasto'] }}</span><span class="wartosc">{{ $dane['adres'] }}</span></span>
					</li>
					<li data-parametr="termin" @if(!$dane['termin']) hidden @endif>
						<img src="{{ asset('img/ikona-kalendarz.svg') }}" width="34" height="34" alt="">
						<span><span class="nazwa">TERMIN ODDANIA:</span><span class="wartosc">{{ $dane['termin'] }}</span></span>
					</li>
					<li data-parametr="metraz" @if(!$dane['metraz']) hidden @endif>
						<img src="{{ asset('img/ikona-metraz.svg') }}" width="34" height="34" alt="">
						<span><span class="nazwa">ZAKRES METRAŻU:</span><span class="wartosc">{{ $dane['metraz'] }}</span></span>
					</li>
					<li data-parametr="cena" @if(!$dane['cena']) hidden @endif>
						<img src="{{ asset('img/ikona-cena.svg') }}" width="34" height="34" alt="">
						<span><span class="nazwa">CENY OD</span><span class="wartosc">{{ $dane['cena'] }}</span></span>
					</li>
				</ul>
				</div>

				<div class="inwestycje-akcje">
					<x-przycisk-pigulka href="{{ $dane['link'] }}" class="na-tle inwestycje-przycisk">ZOBACZ INWESTYCJĘ</x-przycisk-pigulka>
				</div>
			</div>

			<div class="inwestycje-galeria pojawia-sie opoznienie-1">
				<div class="galeria-glowna">
					<picture @foreach($dane as $klucz => $wartosc) data-{{ $klucz }}="{{ $wartosc }}" @endforeach>
						<source type="image/webp" srcset="{{ $pierwszaInwestycja->zdjecieKarty(true) }}">
						<img src="{{ $pierwszaInwestycja->zdjecieKarty() }}" width="1120" height="616" alt="{{ $pierwszaInwestycja->name }} - wizualizacja" loading="lazy">
					</picture>
					<span class="plakietka-status">{{ $pierwszaInwestycja->plakietka() }}</span>
					<p class="pasek-adresu">
						<img src="{{ asset('img/ikona-lokalizacja.svg') }}" width="25" height="25" alt="">
						<span class="tekst-adresu">{{ $dane['pasek'] }}</span>
					</p>
				</div>

				@if($inwestycjeGlowna->count() > 1)
				<div class="galeria-miniatury">
					@foreach($inwestycjeGlowna->skip(1) as $inwestycjaMiniatury)
						<picture @foreach($daneInwestycji($inwestycjaMiniatury) as $klucz => $wartosc) data-{{ $klucz }}="{{ $wartosc }}" @endforeach>
							<source type="image/webp" srcset="{{ $inwestycjaMiniatury->zdjecieKarty(true) }}">
							<img src="{{ $inwestycjaMiniatury->zdjecieKarty() }}" width="469" height="406" alt="{{ $inwestycjaMiniatury->name }}" loading="lazy">
						</picture>
					@endforeach
				</div>

				<div class="galeria-nawigacja">
					<button type="button" class="galeria-poprzednia" aria-label="Poprzednia inwestycja">
						<img src="{{ asset('img/strzalka-poprzednia-duza.svg') }}" width="40" height="40" alt="">
					</button>
					<button type="button" class="galeria-nastepna" aria-label="Następna inwestycja">
						<img src="{{ asset('img/strzalka-nastepna-duza.svg') }}" width="39" height="40" alt="">
					</button>
				</div>
				@endif
			</div>

		</div>
	</section>
	@endif

	<!-- ============ O NAS ============ (edycja: config/sekcje.php, „strona-glowna.o-nas”) -->
	@php
		$oNas = sekcja('strona-glowna.o-nas');
		$zdjecieDuze = $oNas->obrazek('zdjecie_duze');
		$zdjecieMale = $oNas->obrazek('zdjecie_male');
		$przyciskONas = $oNas->link('przycisk');
	@endphp
	<section class="o-nas sekcja-karta"{!! $oNas->edycja() !!}>

		<div class="o-nas-zdjecia pojawia-sie">
			{{-- Zdjęcia z szablonu są kadrowane w CSS (przesunięcie w ramce), wgrane w panelu są już przycięte - klasa "wypelnia" --}}
			<picture>
				<source type="image/webp" srcset="{{ $zdjecieDuze->webp }}">
				<img @class(['duze', 'wypelnia' => $zdjecieDuze->wlasny]) src="{{ $zdjecieDuze->jpg }}" width="{{ $zdjecieDuze->szerokosc }}" height="{{ $zdjecieDuze->wysokosc }}" alt="{{ $zdjecieDuze->alt }}" loading="lazy">
			</picture>
			<picture>
				<source type="image/webp" srcset="{{ $zdjecieMale->webp }}">
				<img @class(['male', 'wypelnia' => $zdjecieMale->wlasny]) src="{{ $zdjecieMale->jpg }}" width="{{ $zdjecieMale->szerokosc }}" height="{{ $zdjecieMale->wysokosc }}" alt="{{ $zdjecieMale->alt }}" loading="lazy">
			</picture>
		</div>

		<div class="o-nas-tresc pojawia-sie opoznienie-1">
			<x-etykieta>{{ $oNas->tekst('etykieta') }}</x-etykieta>
			<h2 class="tytul-sekcji rozjasnia-sie">{{ $oNas->tekst('naglowek') }}@if($oNas->tekst('naglowek_akcent') !== '')<br><span class="akcent">{{ $oNas->tekst('naglowek_akcent') }}</span>@endif</h2>

			<div class="wciecie">
				{!! $oNas->html('tresc') !!}
				@if($przyciskONas->tekst !== '' && $przyciskONas->adres !== '')
					<x-przycisk-pigulka href="{{ $przyciskONas->adres }}" class="na-tle">{{ $przyciskONas->tekst }}</x-przycisk-pigulka>
				@endif
			</div>
		</div>

	</section>

	<!-- ============ LICZBY ============ (edycja: „strona-glowna.liczby”) -->
	@php $liczby = sekcja('strona-glowna.liczby'); $tloLiczb = $liczby->obrazek('tlo'); @endphp
	<section class="liczby" aria-label="Dynamic Development w liczbach"{!! $liczby->edycja() !!}>
		<div class="liczby-tlo">
			<picture>
				<source type="image/webp" srcset="{{ $tloLiczb->webp }}">
				<img src="{{ $tloLiczb->jpg }}" width="{{ $tloLiczb->szerokosc }}" height="{{ $tloLiczb->wysokosc }}" alt="{{ $tloLiczb->alt }}" loading="lazy">
			</picture>
		</div>

		<ul class="liczby-lista">
			@foreach($liczby->lista('liczby') as $i => $liczba)
			<li @class(['liczba', 'pojawia-sie', 'opoznienie-' . $i => $i > 0])>
				<p class="wartosc"><span class="duza-liczba">{{ $liczba->tekst('wartosc') }}</span> <span class="jednostka">{{ $liczba->tekst('jednostka') }}</span></p>
				<span class="opis">{{ $liczba->tekst('opis') }}</span>
				<img class="ozdobnik" src="{{ asset('img/ozdoba-liczby.svg') }}" width="93" height="112" alt="">
			</li>
			@endforeach
		</ul>
	</section>

	<!-- ============ MAPA INWESTYCJI ============ -->
	@php $mapa = sekcja('strona-glowna.mapa'); @endphp
	<section class="mapa-sekcja sekcja-karta"{!! $mapa->edycja() !!}>

		<div class="mapa-gora pojawia-sie">
			<div>
				<x-etykieta>{{ $mapa->tekst('etykieta') }}</x-etykieta>
				<h2 class="tytul-sekcji rozjasnia-sie">{{ $mapa->tekst('naglowek') }}@if($mapa->tekst('naglowek_akcent') !== '')<br><span class="akcent">{{ $mapa->tekst('naglowek_akcent') }}</span>@endif</h2>
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

			<!-- Pinezki z bazy: lat/lng inwestycji przeliczone na % obrazka (config/mapa.php - App\Support\MapaStatyczna).
			     "left"/"top" to czubek pinezki. Dymki nie mają własnych współrzędnych - js/mapa.js stawia je obok
			     pinezki; po lewej stronie, gdy pinezka stoi przy prawej krawędzi albo inna pinezka jest tuż obok. -->
			@php
				$inwestycjeMapy = \App\Models\Investment::frontu()->filter(fn ($i) => $i->pozycjaNaMapie())->values();
			@endphp
			@foreach($inwestycjeMapy as $inwestycjaMapy)
				@php $pozycja = $inwestycjaMapy->pozycjaNaMapie(); @endphp
				<button class="pinezka" type="button" data-inwestycja="{{ $inwestycjaMapy->slug }}" data-status="{{ $inwestycjaMapy->statusNaMapie() }}" style="left:{{ $pozycja['left'] }}%;top:{{ $pozycja['top'] }}%" aria-label="{{ $inwestycjaMapy->name }}">
					<img src="{{ asset('img/pinezka-mapa.svg') }}" width="55" height="69" alt="">
				</button>
			@endforeach

			@foreach($inwestycjeMapy as $inwestycjaMapy)
				@php
					$pozycja = $inwestycjaMapy->pozycjaNaMapie();
					// Sąsiad bliżej niż ~4% szerokości - dymki rozsuwamy w pionie (jak Lake Village / Zielona Polana w makiecie)
					$sasiad = $inwestycjeMapy->first(fn ($inna) => $inna->id !== $inwestycjaMapy->id
						&& abs($inna->pozycjaNaMapie()['left'] - $pozycja['left']) < 4 && abs($inna->pozycjaNaMapie()['top'] - $pozycja['top']) < 6);
					$lewo = $sasiad || $pozycja['left'] > 75;
					$odsuniecie = $sasiad ? ($sasiad->pozycjaNaMapie()['top'] > $pozycja['top'] ? -17 : 17) : null;
					$dostepne = $inwestycjaMapy->liczbaDostepnych();
				@endphp
				<div @class(['dymek-mapy', 'rozwiniety' => $loop->first]) data-dymek="{{ $inwestycjaMapy->slug }}" @if($lewo) data-strona="lewo" @endif @if($odsuniecie) data-odsuniecie="{{ $odsuniecie }}" @endif>
					<button class="nazwa" type="button">{{ $inwestycjaMapy->name }}</button>
					<div class="szczegoly">
						<p class="wiersz"><img src="{{ asset('img/ikona-pin-maly.svg') }}" width="19" height="19" alt=""> {{ collect([\App\Support\FiltryOferty::lokalizacja($inwestycjaMapy), $inwestycjaMapy->address])->filter()->implode(' / ') }}</p>
						<p class="wiersz"><img src="{{ asset('img/ikona-dom.svg') }}" width="19" height="19" alt="">
							@if($inwestycjaMapy->status != 1)
								Inwestycja {{ $inwestycjaMapy->status == 2 ? 'zrealizowana' : 'w realizacji' }}
							@elseif($dostepne)
								Dostępne lokale: <span class="akcent">{{ $dostepne }}</span>
							@else
								Inwestycja w sprzedaży
							@endif
						</p>
						<div class="przyciski">
							<a class="mini-przycisk" href="{{ $inwestycjaMapy->linkMenu() }}" @if($inwestycjaMapy->linkZewnetrzny()) target="_blank" rel="noopener" @endif>Szczegóły inwestycji</a>
							<a class="mini-przycisk obrys" href="{{ $inwestycjaMapy->jakDojechac() }}" target="_blank" rel="noopener"><img src="{{ asset('img/ikona-auto.svg') }}" width="14" height="14" alt=""> Jak dojechać</a>
						</div>
					</div>
				</div>
			@endforeach

			<x-mapa-sterowanie />
		</div>

	</section>

	<!-- ============ DLACZEGO WARTO ============ (edycja: „strona-glowna.dlaczego-warto”) -->
	@php $dlaczego = sekcja('strona-glowna.dlaczego-warto'); $zdjecieDlaczego = $dlaczego->obrazek('zdjecie'); @endphp
	<section class="dlaczego-warto"{!! $dlaczego->edycja() !!}>
		<div class="uklad">

			<div class="dlaczego-zdjecie pojawia-sie">
				<picture>
					<source type="image/webp" srcset="{{ $zdjecieDlaczego->webp }}">
					<img @if($zdjecieDlaczego->wlasny)class="wypelnia" @endif src="{{ $zdjecieDlaczego->jpg }}" width="{{ $zdjecieDlaczego->szerokosc }}" height="{{ $zdjecieDlaczego->wysokosc }}" alt="{{ $zdjecieDlaczego->alt }}" loading="lazy">
				</picture>
			</div>

			<div class="dlaczego-tresc pojawia-sie opoznienie-1">
				<x-etykieta>{{ $dlaczego->tekst('etykieta') }}</x-etykieta>
				<h2 class="tytul-sekcji rozjasnia-sie">{{ $dlaczego->tekst('naglowek') }}@if($dlaczego->tekst('naglowek_akcent') !== '')<br><span class="akcent">{{ $dlaczego->tekst('naglowek_akcent') }}</span>@endif</h2>

				<ul class="zalety">
					@foreach($dlaczego->lista('zalety') as $zaleta)
					@php $ikona = $zaleta->ikona('ikona'); @endphp
					<li>
						<img src="{{ $ikona->src }}" width="{{ $ikona->szerokosc }}" height="{{ $ikona->wysokosc }}" alt="">
						<div>
							<h3>{{ $zaleta->tekst('tytul') }}</h3>
							<p>{!! $zaleta->html('opis') !!}</p>
						</div>
					</li>
					@endforeach
				</ul>
			</div>

		</div>
	</section>

	<!-- ============ KAFLE: FINANSOWANIE I WYKOŃCZENIE ============ -->
	<x-sekcje.kafle />

	@if($aktualnosci->isNotEmpty())
	<!-- ============ AKTUALNOŚCI ============ -->
	@php $naglowekAktualnosci = sekcja('strona-glowna.aktualnosci'); $przyciskAktualnosci = $naglowekAktualnosci->link('przycisk'); @endphp
	<section class="aktualnosci"{!! $naglowekAktualnosci->edycja() !!}>

		<div class="aktualnosci-gora pojawia-sie">
			<div>
				<x-etykieta>{{ $naglowekAktualnosci->tekst('etykieta') }}</x-etykieta>
				<h2 class="tytul-sekcji jasny rozjasnia-sie">{{ $naglowekAktualnosci->tekst('naglowek') }}@if($naglowekAktualnosci->tekst('naglowek_akcent') !== '') <span class="akcent">{{ $naglowekAktualnosci->tekst('naglowek_akcent') }}</span>@endif</h2>
			</div>
			@if($przyciskAktualnosci->tekst !== '' && $przyciskAktualnosci->adres !== '')
				<x-przycisk-pigulka href="{{ $przyciskAktualnosci->adres }}">{{ $przyciskAktualnosci->tekst }}</x-przycisk-pigulka>
			@endif
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
