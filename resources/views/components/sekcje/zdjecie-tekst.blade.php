{{-- Sekcja "zdjęcie po lewej + kolumna tekstu" (section.partner-wykonczenia) - Wykończenie (partner), Poznaj nas (misja, o nas).
     Slot = treść kolumny. Użycie: <x-sekcje.zdjecie-tekst class="partner-misja" zdjecie="poznaj-misja" alt="...">...</x-sekcje.zdjecie-tekst> --}}
@props(['zdjecie', 'alt' => '', 'szerokosc' => 1370, 'wysokosc' => 750])
<section {{ $attributes->class(['partner-wykonczenia']) }}>

	<div class="partner-zdjecie pojawia-sie z-lewej">
		<picture>
			<source type="image/webp" srcset="{{ asset('img/' . $zdjecie . '.webp') }}">
			<img src="{{ asset('img/' . $zdjecie . '.jpg') }}" width="{{ $szerokosc }}" height="{{ $wysokosc }}" alt="{{ $alt }}" loading="lazy">
		</picture>
	</div>

	<div class="tresc">
		<div class="kolumna pojawia-sie">
			{{ $slot }}
		</div>
	</div>

</section>
