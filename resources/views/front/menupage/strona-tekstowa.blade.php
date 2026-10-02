@extends('layouts.page')

{{-- Ogólna strona tekstowa: dowolna strona z panelu (Strony) bez własnego widoku - np. Polityka prywatności, regulamin.
     Nagłówek: "Nagłówek H1" z panelu, inaczej tytuł strony; "Sub-Tytuł" jako wstęp; treść z edytora.
     Wygląd jak strona wpisu (nagłówek podstrony + typografia z resources/less/front/wpis.less).
     Widok dziedziczący może dołożyć coś pod treścią w sekcji 'po_tresci'. --}}

@section('main_class', 'ma-pasek-boczny')
@section('meta_title', $page->title)
@if($page->meta_title) @section('seo_title', $page->meta_title) @endif
@if($page->meta_description) @section('seo_description', $page->meta_description) @endif
@if($page->meta_robots) @section('seo_robots', $page->meta_robots) @endif

@section('content')
	<!-- ============ NAGŁÓWEK PODSTRONY ============ -->
	<section class="naglowek-strony naglowek-wpisow">
		<x-okruszki :sciezka="[$page->title => null]" />

		<h1 class="tytul-sekcji na-srodku rozjasnia-sie">{{ $page->content_header ?: $page->title }}</h1>
		@if($page->title_text)
			<p class="wstep-strony pojawia-sie opoznienie-1">{{ $page->title_text }}</p>
		@endif
	</section>

	<x-pasek-boczny />

	<!-- ============ TREŚĆ ============ -->
	<article class="wpis">
		<div class="wpis-tresc pojawia-sie">
			{!! $page->content !!}

			@yield('po_tresci')
		</div>
	</article>
@endsection
