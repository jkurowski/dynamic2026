{{-- Karta aktualności w karuzeli na stronie głównej. --}}
@props(['tytul', 'zajawka' => '', 'data', 'link', 'kategoria' => null, 'obrazek', 'webp' => null])
<article class="karta-aktualnosci">
	<picture>
		<source type="image/webp" srcset="{{ $webp }}">
		<img src="{{ $obrazek }}" width="547" height="309" alt="" loading="lazy">
	</picture>
	@if($kategoria)
	<span class="plakietka-karty">{{ $kategoria }}</span>
	@endif
	<div class="karta-aktualnosci-tresc">
		<h3>{{ $tytul }}</h3>
		<p>{{ $zajawka }}</p>
	</div>
	<div class="karta-aktualnosci-stopka">
		<time datetime="{{ $data }}">{{ $data }}</time>
		<a href="{{ $link }}" class="przycisk-strzalka" aria-label="Czytaj więcej">
			<img src="{{ asset('img/ikona-strzalka-karta.svg') }}" width="28" height="30" alt="">
		</a>
	</div>
</article>
