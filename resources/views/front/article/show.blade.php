@extends('layouts.page')

{{-- Strona wpisu - poza szablonem HTML (projekt Figma, podstrona Aktualności). Złożona z elementów szablonu,
     style treści w resources/less/front/wpis.less. Do akceptacji z projektem. --}}

@section('main_class', 'ma-pasek-boczny')
@section('meta_title', $article->title)
@section('seo_title', $article->meta_title ?: $article->title . ' - Dynamic Development')
@section('seo_description', $article->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($article->content_entry), 160))
@if($article->meta_robots) @section('seo_robots', $article->meta_robots) @endif

@section('content')
	<!-- ============ NAGŁÓWEK WPISU ============ -->
	<section class="naglowek-strony naglowek-wpisow naglowek-wpisu">
		<x-okruszki :sciezka="['Aktualności' => route('aktualnosci.index'), $article->title => null]" />

		<x-etykieta class="na-srodku pojawia-sie">{{ $article->category ?: 'AKTUALNOŚCI' }}</x-etykieta>
		<h1 class="tytul-sekcji na-srodku rozjasnia-sie">{{ $article->title }}</h1>
		<p class="data-wpisu pojawia-sie opoznienie-1">
			<img src="{{ asset('img/ikona-kalendarz.svg') }}" width="18" height="18" alt="">
			<time datetime="{{ $article->dataPublikacji() }}">{{ \Illuminate\Support\Carbon::parse($article->dataPublikacji())->format('d.m.Y') }}</time>
		</p>
	</section>

	<x-pasek-boczny />

	<!-- ============ TREŚĆ WPISU ============ -->
	<article class="wpis">
		@if($article->file)
			<picture class="wpis-zdjecie pojawia-sie">
				<source type="image/webp" srcset="{{ $article->zdjecie('big', true) }}">
				<img src="{{ $article->zdjecie('big') }}" width="{{ config('images.article.big_width') }}" height="{{ config('images.article.big_height') }}" alt="{{ $article->file_alt ?: $article->title }}" fetchpriority="high">
			</picture>
		@endif

		<div class="wpis-tresc pojawia-sie">
			@if($article->content_entry)
				<p class="wstep">{{ $article->content_entry }}</p>
			@endif
			{!! $article->content !!}
		</div>

		<div class="wpis-powrot">
			<x-przycisk-pigulka href="{{ route('aktualnosci.index') }}" class="na-tle">WSZYSTKIE AKTUALNOŚCI</x-przycisk-pigulka>
		</div>
	</article>

	@if($pozostale->isNotEmpty())
		<!-- ============ POZOSTAŁE WPISY ============ -->
		<section class="lista-wpisow wpis-pozostale" aria-label="Pozostałe wpisy">
			<x-etykieta class="pojawia-sie">NA BIEŻĄCO</x-etykieta>
			<h2 class="tytul-sekcji rozjasnia-sie">Pozostałe <span class="akcent">aktualności</span></h2>
			<div class="row">
				@foreach($pozostale->take(2) as $wpis)
					<div class="col-12 col-md-6">
						<x-karta-wpisu :wpis="$wpis" :opoznienie="$loop->even ? 'opoznienie-1' : null" />
					</div>
				@endforeach
			</div>
		</section>
	@endif
@endsection
