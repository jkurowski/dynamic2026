@extends('layouts.page')

{{-- Lista inwestycji w sprzedaży (szablon dynamic-front/inwestycje.html) --}}
@section('main_class', 'ma-pasek-boczny')
@section('meta_title', $page->title ?? 'Inwestycje')
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset

@section('content')
	<!-- ============ NAGŁÓWEK PODSTRONY ============ -->
	<section class="naglowek-strony">
		<x-okruszki :sciezka="['Inwestycje' => route('inwestycje.index'), 'W sprzedaży' => null]" />
		<div class="naglowek-inwestycji pojawia-sie">
			<div class="tytul">
				<x-etykieta>INWESTYCJE</x-etykieta>
				<h1 class="tytul-sekcji rozjasnia-sie">Inwestycje <span class="akcent">w sprzedaży</span></h1>
			</div>
			<p class="wprowadzenie pojawia-sie">Poznaj inwestycje dopasowane do różnych stylów życia<br class="lamanie-desktop"> – od miejskiego komfortu po spokojną przestrzeń blisko natury</p>
		</div>
	</section>

		<x-pasek-boczny />

	<!-- ============ LISTA INWESTYCJI ============ -->
	<section class="kontener-inwestycji" aria-label="Lista inwestycji w sprzedaży">
		<ul class="siatka-inwestycji kolejno row list-unstyled">
			@forelse($inwestycje as $inwestycja)
				<li class="col-12 col-md-6">
					<x-inwestycje.karta :inwestycja="$inwestycja" :opoznienie="$loop->even ? 'opoznienie-1' : null" />
				</li>
			@empty
				<li class="col-12"><p class="wprowadzenie">Wkrótce pojawią się tu nowe inwestycje.</p></li>
			@endforelse
		</ul>
	</section>
@endsection
