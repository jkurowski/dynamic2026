{{-- Hero podstrony ze zdjęciem po prawej (section.fin-hero) - Finansowanie, Poznaj nas, Wykończenie pod klucz.
     Slot = treść kolumny (etykieta, h1, wstęp, przycisk, atuty).
     Użycie: <x-hero-podstrony class="hero-finansowanie" :sciezka="['Finansowanie' => null]" zdjecie="fin-hero" alt="...">...</x-hero-podstrony> --}}
@props(['sciezka' => [], 'zdjecie', 'alt' => ''])
<section {{ $attributes->class(['fin-hero']) }}>

	<div class="fin-hero-tresc">
		<x-okruszki :sciezka="$sciezka" />

		<div class="fin-hero-kolumna pojawia-sie">
			{{ $slot }}
		</div>
	</div>

	<div class="fin-hero-zdjecie pojawia-sie z-prawej opoznienie-1">
		<picture>
			<source type="image/webp" srcset="{{ asset('img/' . $zdjecie . '.webp') }}">
			<img src="{{ asset('img/' . $zdjecie . '.jpg') }}" width="1370" height="750" alt="{{ $alt }}" fetchpriority="high">
		</picture>
		<span class="fin-hero-ramka" aria-hidden="true"></span>
	</div>

	<x-pasek-boczny />
</section>
