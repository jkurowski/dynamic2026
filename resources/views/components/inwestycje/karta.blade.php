{{-- Karta inwestycji na liście /inwestycje (szablon inwestycje.html). Parametry tylko wypełnione - inwestycja
     bez lokali w panelu nie ma metrażu ani ceny. Bez podstrony: link na stronę inwestycji albo „WKRÓTCE”. --}}
@props(['inwestycja', 'opoznienie' => null])
@php
	$link = $inwestycja->link();
	$parametry = array_filter([
		['ikona-lokalizacja.svg', $inwestycja->location_label, $inwestycja->address],
		$inwestycja->terminOddania() ? ['ikona-kalendarz.svg', 'TERMIN ODDANIA:', $inwestycja->terminOddania()] : null,
		$inwestycja->zakresMetrazu() ? ['ikona-metraz.svg', 'ZAKRES METRAŻU:', $inwestycja->zakresMetrazu()] : null,
		$inwestycja->cenaOd() ? ['ikona-cena.svg', 'CENY OD', $inwestycja->cenaOd()] : null,
	], fn ($p) => $p && ($p[1] || $p[2]));
@endphp
<article @class(['karta-inwestycji', 'pojawia-sie', $opoznienie]) id="{{ $inwestycja->slug }}">
	<div class="zdjecie">
		<picture>
			<source type="image/webp" srcset="{{ $inwestycja->zdjecieKarty(true) }}">
			<img src="{{ $inwestycja->zdjecieKarty() }}" width="843" height="464" alt="Wizualizacja inwestycji {{ $inwestycja->name }}" loading="lazy">
		</picture>
		<span class="plakietka-inwestycji">{{ $inwestycja->plakietka() }}</span>
	</div>
	<div class="karta-inwestycji-dol">
		<div class="opis">
			<h2>{{ $inwestycja->name }}</h2>
			@if($link)
				<a class="przycisk-inwestycji" href="{{ $link }}" @if($inwestycja->linkZewnetrzny()) target="_blank" rel="noopener" @endif>ZOBACZ INWESTYCJĘ</a>
			@else
				<span class="przycisk-inwestycji">WKRÓTCE</span>
			@endif
		</div>
		<ul class="parametry-karty">
			@foreach($parametry as [$ikona, $nazwa, $wartosc])
				<li>
					<img src="{{ asset('img/' . $ikona) }}" width="27" height="27" alt="">
					<span><span class="nazwa">{{ $nazwa }}</span><span class="wartosc">{{ $wartosc }}</span></span>
				</li>
			@endforeach
		</ul>
	</div>
</article>
