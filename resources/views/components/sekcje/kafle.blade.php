{{-- Kafle Finansowanie / Wykończenie pod klucz - strona główna i podstrona inwestycji (tam: tag="section" class="oferta-uzupelniajaca"). --}}
@props(['tag' => 'div'])
<{{ $tag }} {{ $attributes->class(['kafle']) }}>
	<div class="row">

		<div class="col-12 col-md-6">
			<a class="kafel pojawia-sie" href="{{ route('menu.show', ['uri' => 'finansowanie']) }}">
				<picture>
					<source type="image/webp" srcset="{{ asset('img/kafel-finansowanie.webp') }}">
					<img src="{{ asset('img/kafel-finansowanie.jpg') }}" width="830" height="482" alt="" loading="lazy">
				</picture>
				<x-etykieta>FINANSOWANIE</x-etykieta>
				<h2 class="tytul-sekcji rozjasnia-sie">Sprawdź swoją ratę,<br><span class="akcent">zanim kupisz</span></h2>
				<x-przycisk-pigulka>SPRAWDŹ SWOJĄ RATĘ</x-przycisk-pigulka>
			</a>
		</div>

		<div class="col-12 col-md-6">
			<a class="kafel pojawia-sie opoznienie-1" href="{{ route('menu.show', ['uri' => 'wykonczenie-pod-klucz']) }}">
				<picture>
					<source type="image/webp" srcset="{{ asset('img/kafel-wykonczenie.webp') }}">
					<img src="{{ asset('img/kafel-wykonczenie.jpg') }}" width="830" height="482" alt="" loading="lazy">
				</picture>
				<x-etykieta>WYKOŃCZENIE POD KLUCZ</x-etykieta>
				<h2 class="tytul-sekcji rozjasnia-sie">Zamieszkaj od razu<br><span class="akcent">po odbiorze</span></h2>
				<x-przycisk-pigulka>SPRAWDŹ OFERTĘ</x-przycisk-pigulka>
			</a>
		</div>

	</div>
</{{ $tag }}>
