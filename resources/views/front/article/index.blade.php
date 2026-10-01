@extends('layouts.page')

@section('main_class', 'ma-pasek-boczny')
@section('meta_title', $page->title ?? 'Aktualności')
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset

@section('content')
	<!-- ============ NAGŁÓWEK PODSTRONY ============ -->
	<section class="naglowek-strony naglowek-wpisow">
		<x-okruszki :sciezka="['Aktualności' => null]" />

		<x-etykieta class="na-srodku pojawia-sie">NA BIEŻĄCO</x-etykieta>
		<h1 class="tytul-sekcji na-srodku rozjasnia-sie">Wszystkie <span class="akcent">aktualności</span></h1>
		<p class="wstep-strony pojawia-sie opoznienie-1">Szukasz inspiracji odnośnie tego, jak urządzić swoje mieszkanie? A może nurtują Cię kwestie formalne związane z zakupem nieruchomości? Na naszym blogu przedstawiamy Ci garść porad i wskazówek, które pomogą Ci znaleźć odpowiedź na każdą wątpliwość!</p>
	</section>

		<x-pasek-boczny />

	<!-- ============ LISTA WPISÓW ============ -->
	<section class="lista-wpisow" aria-label="Lista wpisów">
		<div class="row">
			@forelse($articles as $wpis)
				<div class="col-12 col-md-6">
					<x-karta-wpisu :wpis="$wpis" :opoznienie="$loop->even ? 'opoznienie-1' : null" />
				</div>
			@empty
				<div class="col-12">
					<p class="wstep-strony">Wkrótce pojawią się tu nowe wpisy.</p>
				</div>
			@endforelse
		</div>
	</section>

	<!-- ============ PAGINACJA ============ -->
	<x-paginacja :lista="$articles" etykieta="Stronicowanie listy wpisów" />
@endsection

