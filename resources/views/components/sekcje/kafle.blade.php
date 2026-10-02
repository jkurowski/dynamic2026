{{-- Kafle Finansowanie / Wykończenie pod klucz - strona główna i podstrona inwestycji (tam: tag="section" class="oferta-uzupelniajaca").
     Treść: sekcja „kafle” (config/sekcje.php), jedna dla wszystkich stron. --}}
@props(['tag' => 'div'])
@php $kafle = sekcja('kafle'); @endphp
<{{ $tag }} {{ $attributes->class(['kafle']) }}{!! $kafle->edycja() !!}>
	<div class="row">
		@foreach($kafle->lista('kafle') as $i => $kafel)
		@php $zdjecie = $kafel->obrazek('zdjecie'); $przycisk = $kafel->link('przycisk'); @endphp

		<div class="col-12 col-md-6">
			<a @class(['kafel', 'pojawia-sie', 'opoznienie-' . $i => $i > 0]) href="{{ $przycisk->adres }}">
				<picture>
					<source type="image/webp" srcset="{{ $zdjecie->webp }}">
					<img src="{{ $zdjecie->jpg }}" width="{{ $zdjecie->szerokosc }}" height="{{ $zdjecie->wysokosc }}" alt="{{ $zdjecie->alt }}" loading="lazy">
				</picture>
				<x-etykieta>{{ $kafel->tekst('etykieta') }}</x-etykieta>
				<h2 class="tytul-sekcji rozjasnia-sie">{{ $kafel->tekst('naglowek') }}@if($kafel->tekst('naglowek_akcent') !== '')<br><span class="akcent">{{ $kafel->tekst('naglowek_akcent') }}</span>@endif</h2>
				@if($przycisk->tekst !== '')
					<x-przycisk-pigulka>{{ $przycisk->tekst }}</x-przycisk-pigulka>
				@endif
			</a>
		</div>
		@endforeach

	</div>
</{{ $tag }}>
