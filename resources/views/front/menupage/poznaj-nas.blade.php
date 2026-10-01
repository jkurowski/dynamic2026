@extends('layouts.page')

@section('body_class', 'strona-poznaj-nas')
@section('meta_title', $page->title)
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset

@section('content')
	<!-- ============ NAGŁÓWEK PODSTRONY ============ -->
	<x-hero-podstrony class="hero-poznaj" :sciezka="['Poznaj nas' => null]" zdjecie="poznaj-hero" alt="Wizualizacja inwestycji Dynamic Development">
		<x-etykieta>O FIRMIE</x-etykieta>
		<h1 class="tytul-sekcji jasny rozjasnia-sie">Poznaj bliżej<br><span class="akcent">Dynamic Development</span></h1>

		<div class="wstep pojawia-sie">
			<p>Dynamic Development to firma rodzinna z kapitałem w 100% pochodzącym z Polski. Od ponad 20 lat tworzymy komfortowe domy, mieszkania i przestrzenie do życia, które odpowiadają na potrzeby mieszkańców</p>
		</div>

		<div class="para-przyciskow pojawia-sie opoznienie-1">
			<a href="{{ url('inwestycje') }}" class="przycisk-pigulka pelny">
				POZNAJ INWESTYCJE
			</a>
			<x-przycisk-pigulka href="#kontakt" class="na-tle">SKONTAKTUJ SIĘ Z NAMI</x-przycisk-pigulka>
		</div>
	</x-hero-podstrony>

	<!-- ============ LICZBY ============ -->
	<section class="liczby-firmy" aria-label="Dynamic Development w liczbach">
		<ul class="korzysci-lista">
			<li class="kafel-liczby pojawia-sie ">
				<img src="{{ asset('img/poznaj-ikona-lata.svg') }}" width="75" height="75" alt="">
				<span class="duza-liczba">20+</span>
				<h2>lat doświadczenia<br>w branży</h2>
				<span class="kreska" aria-hidden="true"></span>
			</li>
			<li class="kafel-liczby pojawia-sie opoznienie-1">
				<img src="{{ asset('img/poznaj-ikona-lokale.svg') }}" width="75" height="75" alt="">
				<span class="duza-liczba">1000+</span>
				<h2>lokali mieszkalnych<br>i użytkowych</h2>
				<span class="kreska" aria-hidden="true"></span>
			</li>
			<li class="kafel-liczby pojawia-sie opoznienie-2">
				<img src="{{ asset('img/poznaj-ikona-kapital.svg') }}" width="75" height="75" alt="">
				<span class="duza-liczba">100%</span>
				<h2>kapitał pochodzący<br>z Polski</h2>
				<span class="kreska" aria-hidden="true"></span>
			</li>
			<li class="kafel-liczby pojawia-sie opoznienie-3">
				<img src="{{ asset('img/poznaj-ikona-metry.svg') }}" width="75" height="75" alt="">
				<span class="duza-liczba">40</span>
				<h2>tys. metrów<br>kwadratowych</h2>
				<span class="kreska" aria-hidden="true"></span>
			</li>
		</ul>
	</section>

	<!-- ============ NASZA MISJA ============ -->
	<x-sekcje.zdjecie-tekst class="partner-misja" zdjecie="poznaj-misja" alt="Osiedle zrealizowane przez Dynamic Development">
		<x-etykieta>NASZA MISJA</x-etykieta>
		<h2 class="tytul-sekcji rozjasnia-sie">Realizujemy marzenia<br><span class="akcent">o własnym miejscu</span></h2>
		<p>Naszą misją jest wspieranie Klientów w realizacji marzeń i każdego dnia staramy się ten cel realizować w 100%. Podczas pracy kierujemy się fundamentalnymi zasadami: zaufanie, zrozumienie, precyzja działania oraz unikalność. To właśnie one znacznie wyróżniają nas na tle konkurencji.</p>
		<p>Korzystamy z wyjątkowych rozwiązań oraz wysokiej jakości materiałów. Nasze domy, mieszkania, a także kameralne osiedla i apartamentowce spełniają potrzeby nawet najbardziej wymagających. Pomożemy zrealizować Twoje marzenia o własnym, nieprzeciętnym miejscu do życia, w którym będziesz czuł się wyjątkowo.</p>
	</x-sekcje.zdjecie-tekst>

	<!-- ============ CYTAT ============ -->
	<section class="cytat">
		<blockquote class="pojawia-sie">
			<span class="cudzyslow" aria-hidden="true"><img src="{{ asset('img/cudzyslow.svg') }}" width="193" height="129" alt=""></span>
			<p>Naszą misją jest wspierać klientów w realizacji marzeń o własnym miejscu do życia. Każdą inwestycję realizujemy tak, jak <span class="akcent">chcielibyśmy mieszkać sami<br>- z dbałością o detale, jakość i komfort na lata</span></p>
		</blockquote>
	</section>

	<!-- ============ NAGRODY ============ -->
	<section class="nagrody">

		<div class="nagrody-zdjecie pojawia-sie z-prawej">
			<picture>
				<source type="image/webp" srcset="{{ asset('img/poznaj-nagrody.webp') }}">
				<img src="{{ asset('img/poznaj-nagrody.jpg') }}" width="1100" height="619" alt="Realizacja nagrodzona tytułem Dewelopera Roku" loading="lazy">
			</picture>
		</div>
		<span class="ramka-nagrod" aria-hidden="true"></span>

		<div class="tresc">
			<div class="kolumna pojawia-sie">
				<x-etykieta>NAGRODY</x-etykieta>
				<h2 class="tytul-sekcji jasny rozjasnia-sie">Jakość potwierdzona<br><span class="akcent">wyróżnieniem</span></h2>
				<p>Wysoka jakość naszych realizacji została potwierdzona tytułem <strong>Dewelopera Roku 2015</strong>, przyznanym podczas Piaseczyńskich Targów Nieruchomości.</p>
				<p>To dla nas potwierdzenie, że konsekwentne stawianie na jakość, funkcjonalność i odpowiedzialne podejście do realizacji inwestycji ma realne znaczenie dla Klientów.</p>
			</div>
		</div>

	</section>

	<!-- ============ O NAS ============ -->
	<x-sekcje.zdjecie-tekst class="partner-o-nas" zdjecie="poznaj-onas" alt="Zespół Dynamic Development">
		<x-etykieta>O NAS</x-etykieta>
		<h2 class="tytul-sekcji rozjasnia-sie">Firma rodzinna<br><span class="akcent">z doświadczeniem</span></h2>
		<p>Jesteśmy firmą z <strong>bogatymi tradycjami rodzinnymi</strong>, co w połączeniu z naszym doświadczeniem pozwoliło nam jeszcze lepiej zrozumieć potrzeby współczesnych Klientów. Wiemy dobrze, że nieruchomości mają być nie tylko użyteczne i funkcjonalne, ale także i nowoczesne.</p>
		<p>Od początku naszej działalności wybudowaliśmy blisko <strong>1000 lokali mieszkalnych i użytkowych</strong>, których łączna powierzchnia wynosi ponad <strong>40 tysięcy metrów kwadratowych</strong>. Oferowane przez nas mieszkania i domy to nieruchomości z różnych segmentów cenowych, które łączy jedno – możliwie najwyższa jakość, a także niebanalny design.</p>
	</x-sekcje.zdjecie-tekst>

	<!-- ============ HISTORIA FIRMY ============ -->
	<section class="historia">
		<div class="gora pojawia-sie">
			<div>
				<x-etykieta>HISTORIA FIRMY</x-etykieta>
				<h2 class="tytul-sekcji jasny rozjasnia-sie">Budujemy zaufanie<br><span class="akcent">od pokoleń</span></h2>
			</div>
			<div class="opis pojawia-sie">
				<p>Aktywnie uczestniczymy w życiu społecznym mieszkańców i należymy do Polskiego Związku Firm Deweloperskich</p>
				<picture>
					<source type="image/webp" srcset="{{ asset('img/poznaj-pzfd.webp') }}">
					<img class="logo-zwiazku" src="{{ asset('img/poznaj-pzfd.png') }}" width="312" height="111" alt="Polski Związek Firm Deweloperskich" loading="lazy">
				</picture>
			</div>
		</div>
	</section>

	<div class="kadr-historii">
		<div class="tasma-historii pojawia-sie">
			<picture class="szerokie">
				<source type="image/webp" srcset="{{ asset('img/poznaj-hist-1.webp') }}">
				<img src="{{ asset('img/poznaj-hist-1.jpg') }}" width="546" height="500" alt="Realizacja Dynamic Development" loading="lazy">
			</picture>
			<picture>
				<source type="image/webp" srcset="{{ asset('img/poznaj-hist-2.webp') }}">
				<img src="{{ asset('img/poznaj-hist-2.jpg') }}" width="405" height="500" alt="Realizacja Dynamic Development" loading="lazy">
			</picture>
			<picture class="szerokie">
				<source type="image/webp" srcset="{{ asset('img/poznaj-hist-3.webp') }}">
				<img src="{{ asset('img/poznaj-hist-3.jpg') }}" width="546" height="500" alt="Realizacja Dynamic Development" loading="lazy">
			</picture>
			<picture>
				<source type="image/webp" srcset="{{ asset('img/poznaj-hist-4.webp') }}">
				<img src="{{ asset('img/poznaj-hist-4.jpg') }}" width="405" height="500" alt="Realizacja Dynamic Development" loading="lazy">
			</picture>
			<picture>
				<source type="image/webp" srcset="{{ asset('img/poznaj-hist-5.webp') }}">
				<img src="{{ asset('img/poznaj-hist-5.jpg') }}" width="405" height="500" alt="Realizacja Dynamic Development" loading="lazy">
		</picture>
		</div>
		<div class="nawigacja-historii pojawia-sie opoznienie-2" aria-label="Galeria historii firmy">
			<button type="button" class="historia-poprzednia" aria-label="Poprzednie zdjęcie">
				<img src="{{ asset('img/strzalka-poprzednia-duza.svg') }}" width="40" height="40" alt="">
			</button>
			<button type="button" class="historia-nastepna" aria-label="Następne zdjęcie">
				<img src="{{ asset('img/strzalka-nastepna-duza.svg') }}" width="39" height="40" alt="">
			</button>
		</div>
	</div>

	<x-sekcje.kontakt />
@endsection

@push('scripts')
	<script src="{{ asset('js/karuzele.js') }}"></script>
	<script src="{{ asset('js/formularz.js') }}"></script>
@endpush
