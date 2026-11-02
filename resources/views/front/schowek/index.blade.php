@extends('layouts.page')

{{-- Schowek: porównanie zapisanych lokali + zbiorcze zapytanie (wzorzec: schowek Kaltera, w szablonie brak tej strony -
     złożona z elementów wyszukiwarki). Lista lokali z przeglądarki (glowny.js) w adresie: /schowek?lokale=12,15. --}}
@section('body_class', 'strona-wynikow strona-schowka')
@section('main_class', 'ma-pasek-boczny')
@section('meta_title', 'Schowek')
@section('seo_title', 'Schowek - porównanie zapisanych ofert - Dynamic Development')
@section('seo_robots', 'noindex, follow')

@section('content')
	<!-- ============ NAGŁÓWEK PODSTRONY ============ -->
	<section class="naglowek-strony naglowek-wynikow">
		<x-okruszki :sciezka="['Wybierz nieruchomość' => route('wyszukiwarka'), 'Schowek' => null]" />
		<x-etykieta class="na-srodku pojawia-sie">SCHOWEK</x-etykieta>
		<h1 class="tytul-sekcji na-srodku rozjasnia-sie">Porównaj <span class="akcent">zapisane oferty</span></h1>
	</section>

	<x-pasek-boczny />

	<!-- ============ PORÓWNANIE ============ -->
	<section class="porownanie-ofert pojawia-sie" data-schowek="{{ $lokale->pluck('id')->implode(',') }}" aria-label="Porównanie zapisanych ofert">
		@if($lokale->isEmpty())
			<div class="schowek-pusty">
				<p>Nie masz jeszcze zapisanych ofert. Dodaj je do schowka ikoną serca przy lokalu, a potem porównaj je tutaj i zapytaj o wszystkie naraz.</p>
				<x-przycisk-pigulka href="{{ route('wyszukiwarka') }}" class="na-tle">WYBIERZ NIERUCHOMOŚĆ</x-przycisk-pigulka>
			</div>
		@else
			<p class="pasek-schowka">
				Zapisane oferty: <strong>{{ $lokale->count() }}</strong>
				@if($lokale->count() > 1)<span class="legenda-roznic">wyróżnione wiersze - parametry, którymi oferty się różnią</span>@endif
			</p>

			<div class="przewijanie-porownania" tabindex="0">
				<table class="tabela-porownania">
					<thead>
						<tr>
							<th scope="col"><span class="visually-hidden">Parametr</span></th>
							@foreach($lokale as $lokal)
								<th scope="col" data-lokal="{{ $lokal->id }}">
									<a class="rzut-porownania" href="{{ $lokal->link() }}">
										<picture>
											<source type="image/webp" srcset="{{ $lokal->rzut('thumbs', true) }}">
											<img src="{{ $lokal->rzut('thumbs') }}" width="260" height="190" alt="Rzut: {{ $lokal->nazwaPelna() }}" loading="lazy">
										</picture>
									</a>
									<a class="nazwa-lokalu" href="{{ $lokal->link() }}">{{ $lokal->nazwaTypu() }} <span class="akcent">{{ $lokal->numerLokalu() }}</span></a>
									<span class="cena">{{ $lokal->maCene() ? $lokal->cena() : ($lokal->sprzedany() ? '—' : 'Zapytaj o cenę') }}</span>
									<span class="status {{ $lokal->statusKlasa() }}">{{ $lokal->statusNapis() }}</span>
								</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach($wiersze as $wiersz)
							<tr @class(['rozne' => $wiersz['rozne']])>
								<th scope="row">{{ $wiersz['nazwa'] }}</th>
								@foreach($wiersz['wartosci'] as $wartosc)
									<td>{{ $wartosc ?? '—' }}</td>
								@endforeach
							</tr>
						@endforeach
						<tr class="akcje-porownania">
							<th scope="row"><span class="visually-hidden">Akcje</span></th>
							@foreach($lokale as $lokal)
								<td>
									<a class="przycisk-sprawdz" href="{{ $lokal->link() }}">SPRAWDŹ</a>
									<button class="przycisk-sprawdz usun-ze-schowka" type="button" data-ulubione-id="{{ $lokal->idUlubionych() }}"
										aria-label="Usuń {{ mb_strtolower($lokal->nazwaPelna()) }} ze schowka">USUŃ</button>
								</td>
							@endforeach
						</tr>
					</tbody>
				</table>
			</div>
		@endif
	</section>

	@if($lokale->isNotEmpty())
		<!-- ============ ZBIORCZE ZAPYTANIE ============ -->
		<x-sekcje.kontakt strona="Schowek - zapytanie o {{ $lokale->count() }} {{ $lokale->count() === 1 ? 'ofertę' : ($lokale->count() < 5 ? 'oferty' : 'ofert') }}"
			:lokale="$lokale" tytul='Zapytaj o<br><span class="akcent">wybrane oferty</span>' />
	@endif
@endsection
