{{-- Karta wpisu na liście aktualności (article.karta-wpisu). Zdjęcie: miniatura wpisu (896x504, JPG + WebP),
     klasa zdjecie-cms wyłącza przesunięcia kadru dobrane w szablonie pod zdjęcia z makiety (object-fit: cover). --}}
@props(['wpis', 'opoznienie' => null])
<article {{ $attributes->class(['karta-wpisu', 'pojawia-sie', $opoznienie]) }}>
	<picture>
		<source type="image/webp" srcset="{{ $wpis->zdjecie('thumb', true) }}">
		<img class="zdjecie-cms" src="{{ $wpis->zdjecie('thumb') }}" width="{{ config('images.article.thumb_width') }}" height="{{ config('images.article.thumb_height') }}" alt="{{ $wpis->file_alt }}" loading="lazy">
	</picture>
	<div class="karta-wpisu-tresc">
		@if($wpis->category)<span class="kategoria">{{ $wpis->category }}</span>@endif
		<h2><a href="{{ $wpis->link() }}">{{ $wpis->title }}</a></h2>
		<div class="karta-wpisu-stopka">
			<p class="data-wpisu">
				<img src="{{ asset('img/ikona-kalendarz.svg') }}" width="18" height="18" alt="">
				<time datetime="{{ $wpis->dataPublikacji() }}">{{ \Illuminate\Support\Carbon::parse($wpis->dataPublikacji())->format('d.m.Y') }}</time>
			</p>
			<a class="przycisk-czytaj" href="{{ $wpis->link() }}">CZYTAJ DALEJ</a>
		</div>
	</div>
</article>
