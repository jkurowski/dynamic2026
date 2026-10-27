@extends('layouts.page')

{{-- Karta lokalu (szablon dynamic-front/lokal.html). Ceny, historia ceny i koszty dodatkowe z panelu;
     przyciski zależne od statusu tego lokalu (sprzedany - bez ceny i kalkulatora, „Zapytaj o podobne”). --}}
@section('body_class', 'strona-lokalu')
@section('main_class', 'ma-pasek-boczny')
@section('meta_title', $lokal->nazwaPelna() . ' - ' . $inwestycja->name)
@section('seo_title', $lokal->nazwaPelna() . ' - ' . $inwestycja->name . ' - Dynamic Development')
@section('seo_description', $lokal->nazwaPelna() . ' w inwestycji ' . $inwestycja->name . ': ' . $lokal->metraz() . ($lokal->pokazujPokoje() ? ', ' . $lokal->rooms . ' pok.' : '') . ($lokal->pokazujPietro() ? ', piętro ' . $lokal->pietro() : '') . '.')

@php
	$promocja = $lokal->highlighted && $lokal->promotion_price > 0 && $lokal->promotion_price < $lokal->price_brutto && !$lokal->sprzedany();
	$cenaAktualna = $promocja ? $lokal->promotion_price : $lokal->price_brutto;
	$kafelWykonczenia = sekcja('kafle')->lista('kafle')[1] ?? null;
	// Założenia raty - Sekcje stron „kalkulator-raty” (wspólne ze stroną Finansowanie)
	$kalk = sekcja('kalkulator-raty');
	$wklad = (int) $kalk->liczba('wklad');
	$pdf = $lokal->kartaPdf();
	$prospekt = $inwestycja->file_brochure && \Illuminate\Support\Facades\File::isFile(public_path('investment/brochure/' . $inwestycja->file_brochure))
		? asset('investment/brochure/' . $inwestycja->file_brochure) : null;
@endphp

@section('content')
	<!-- ============ NAGŁÓWEK PODSTRONY ============ -->
	<section class="naglowek-strony pojawia-sie">
		<x-okruszki :sciezka="['Inwestycje' => route('inwestycje.index'), $inwestycja->name => route('inwestycje.show', $inwestycja->slug), $lokal->nazwaPelna() => null]" />
	</section>

		<x-pasek-boczny />

	<nav class="nawigacja-lokalu" aria-label="Nawigacja między lokalami">
		<a class="poprzedni" href="{{ $poprzedni ? $poprzedni->link() : route('inwestycje.show', $inwestycja->slug) . '#mieszkania' }}">
			<span class="kolko" aria-hidden="true"><img src="{{ asset('img/lokal-strzalka-poprzednia.svg') }}" width="12" height="12" alt=""></span>
			<span>{{ $poprzedni ? 'Poprzednie' : 'Wszystkie lokale' }}</span>
		</a>
		<a class="plan-pietra" href="#plan-pietra">Rzut</a>
		<a class="nastepny" href="{{ $nastepny ? $nastepny->link() : route('inwestycje.show', $inwestycja->slug) . '#mieszkania' }}">
			<span>{{ $nastepny ? 'Następne' : 'Wszystkie lokale' }}</span>
			<span class="kolko" aria-hidden="true"><img src="{{ asset('img/lokal-strzalka-nastepna.svg') }}" width="12" height="12" alt=""></span>
		</a>
	</nav>

	<!-- ============ KARTA LOKALU I RZUT ============ -->
	<div class="lokal-uklad">
		<section class="karta-lokalu pojawia-sie">
			<div class="plakietki-lokalu">
				<span class="{{ $lokal->statusKlasa() }}"><span class="tekst">{{ $lokal->statusNapis() }}</span></span>
				@if($promocja)
					<span class="promocja"><span class="tekst">PROMOCJA</span></span>
				@endif
			</div>
			<x-etykieta>{{ mb_strtoupper($inwestycja->name) }}</x-etykieta>
			<h1>{{ $lokal->nazwaTypu() }} <span class="akcent">{{ $lokal->numerLokalu() }}</span></h1>
			<ul class="parametry-lokalu">
				<li>
					<img src="{{ asset('img/lokal-ikona-metraz.svg') }}" width="52" height="52" alt="">
					<span class="nazwa">POWIERZCHNIA</span>
					<span class="wartosc">{{ $lokal->metraz() }}</span>
				</li>
				@if($lokal->pokazujPokoje())
				<li>
					<img src="{{ asset('img/lokal-ikona-pokoje.svg') }}" width="52" height="52" alt="">
					<span class="nazwa">LICZBA POKOI</span>
					<span class="wartosc">{{ $lokal->rooms }}</span>
				</li>
				@endif
				@if($lokal->pokazujPietro())
				<li>
					<img src="{{ asset('img/lokal-ikona-pietro.svg') }}" width="52" height="52" alt="">
					<span class="nazwa">PIĘTRO</span>
					<span class="wartosc">{{ $lokal->pietro() }}</span>
				</li>
				@endif
				@if($lokal->pokazujDzialke())
				<li>
					<img src="{{ asset('img/lokal-ikona-dzialka.svg') }}" width="52" height="52" alt="">
					<span class="nazwa">DZIAŁKA</span>
					<span class="wartosc">{{ $lokal->dzialka() }}</span>
				</li>
				@endif
			</ul>

			<div class="ceny-lokalu">
				<span class="etykieta">CENA</span>
				@if($lokal->maCene())
					<div class="wiersz">
						<span class="cena">{{ \App\Support\Format::cena($cenaAktualna) }}</span>
						@if($promocja)<span class="przed-rabatem">{{ $lokal->cena() }}</span>@endif
					</div>
					<div class="wiersz">
						<span class="cena-metr">{{ \App\Support\Format::cenaMetra($cenaAktualna / $lokal->area) }}</span>
						@if($promocja)<span class="przed-rabatem cena-metr">{{ $lokal->cenaMetra() }}</span>@endif
					</div>
					@if($promocja)
						<span class="rabat">Rabat {{ \App\Support\Format::cena($lokal->price_brutto - $lokal->promotion_price) }}</span>
					@endif
				@else
					<div class="wiersz"><span class="cena">{{ $lokal->sprzedany() ? 'Lokal sprzedany' : 'Zapytaj o cenę' }}</span></div>
				@endif

				@if($lokal->priceHistory->isNotEmpty() && !$lokal->sprzedany())
					<button class="przycisk-historii" type="button" data-bs-toggle="collapse"
					        data-bs-target="#historiaCeny" aria-expanded="false" aria-controls="historiaCeny">
						HISTORIA CENY
					</button>
					<div class="collapse historia-ceny" id="historiaCeny">
						<div class="zaslona-historii" data-bs-toggle="collapse" data-bs-target="#historiaCeny"></div>
						<div class="historia-okno">
							<h2><span class="podpis">Historia ceny:</span> {{ $lokal->nazwaTypu() }} <span class="akcent">{{ $lokal->numerLokalu() }}</span></h2>
							<button class="historia-zamknij" type="button" data-bs-toggle="collapse"
							        data-bs-target="#historiaCeny" aria-label="Zamknij historię ceny"></button>
							<table>
								<thead>
									<tr><th>Stara cena</th><th>Nowa cena</th><th>Data zmiany</th></tr>
								</thead>
								<tbody>
									@foreach($lokal->priceHistory as $zmiana)
										@php $metry = $zmiana->area > 0 ? $zmiana->area : $lokal->area; @endphp
										<tr>
											<td>
												<img class="ikona-ceny" src="{{ asset('img/lokal-ikona-cena.svg') }}" width="19" height="19" alt="">
												<span class="kwota">{{ \App\Support\Format::kwota($zmiana->price_brutto) }}<span class="metr">{{ \App\Support\Format::cenaMetra($zmiana->price_brutto / $metry) }}</span></span>
											</td>
											<td><span class="kwota">{{ \App\Support\Format::kwota($zmiana->new_price_brutto) }}<span class="metr">{{ \App\Support\Format::cenaMetra($zmiana->new_price_brutto / $metry) }}</span></span></td>
											<td>{{ \Carbon\Carbon::parse($zmiana->changed_at)->format('d.m.Y H:i') }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				@endif
			</div>

			@if($cechy = $lokal->cechy())
				<ul class="cechy-lokalu">
					@foreach($cechy as [$ikona, $nazwa, $wartosc])
						<li>
							<img src="{{ asset('img/' . $ikona) }}" width="23" height="23" alt="">
							<span class="nazwa">{{ $nazwa }}</span>
							<span class="wartosc">{{ $wartosc }}</span>
						</li>
					@endforeach
				</ul>
			@endif

			@if($lokal->priceComponents->isNotEmpty())
				<h2 class="tytul-kosztow">Dodatkowe koszty i opcje</h2>
				<ul class="koszty-lokalu">
					@foreach($lokal->priceComponents as $skladnik)
						<li>
							<span class="nazwa">{{ $skladnik->name }}</span>
							<span class="wartosc">{{ $skladnik->pivot->value > 0 ? \App\Support\Format::kwota($skladnik->pivot->value) : ((int) $skladnik->pivot->category === 1 ? 'Obowiązkowy' : 'Opcjonalny') }}</span>
						</li>
					@endforeach
				</ul>
			@endif

			<div class="akcje-lokalu">
				<a class="glowny" href="#kontakt">{{ $lokal->sprzedany() ? 'ZAPYTAJ O PODOBNE' : 'ZAPYTAJ O ' . mb_strtoupper($lokal->nazwaTypu()) }}</a>
				@if($pdf)
					<a class="dodatkowy" href="{{ $pdf }}" download>POBIERZ KARTĘ PDF</a>
				@endif
				@if($lokal->walk_3d)
					<a class="dodatkowy" href="{{ $lokal->walk_3d }}" target="_blank" rel="noopener">SPACER 3D</a>
				@endif
				<button class="dodatkowy ulubione" type="button"
					data-ulubione-id="{{ $lokal->idUlubionych() }}"
					data-nazwa-ulubionego="{{ $lokal->nazwaPelna() }} – {{ $inwestycja->name }}"
					aria-label="Dodaj {{ mb_strtolower($lokal->nazwaPelna()) }} do ulubionych" aria-pressed="false">
					<img src="{{ asset('img/ikona-serce.svg') }}" width="35" height="35" alt="">
				</button>
			</div>
			@if($prospekt)
				<a class="prospekt" href="{{ $prospekt }}" target="_blank" rel="noopener">Prospekt informacyjny</a>
			@endif
		</section>
		<div class="karta-rzutu pojawia-sie opoznienie-1" id="plan-pietra">
			<a href="{{ $lokal->rzut() }}" target="_blank" rel="noopener" aria-label="Rzut w pełnym rozmiarze">
				<picture>
					<source type="image/webp" srcset="{{ $lokal->rzut('', true) }}">
					<img src="{{ $lokal->rzut() }}" width="664" height="672" alt="Rzut - {{ $lokal->nazwaPelna() }} w inwestycji {{ $inwestycja->name }}">
				</picture>
			</a>
		</div>
	</div>

	<!-- ============ KALKULATOR RATY I WYKOŃCZENIE ============ -->
	<div class="kafle sekcja-karta">
		<div class="row">
			@if($lokal->maCene())
			<div class="col-12 col-md-6">
				<section class="kafel-kalkulatora pojawia-sie" id="kalkulatorRaty" data-format-kwoty="grosze-kropki" data-oprocentowanie="{{ $kalk->liczba('oprocentowanie') }}"{!! $kalk->edycja() !!}>
					<picture>
						<source type="image/webp" srcset="{{ asset('img/kafel-finansowanie.webp') }}">
						<img src="{{ asset('img/kafel-finansowanie.jpg') }}" width="830" height="482" alt="" loading="lazy">
					</picture>
					<h2>Oblicz ratę dla tej nieruchomości</h2>
					<div class="suwak">
						<div class="opis">
							<label class="podpis" for="cenaLokalu">Cena nieruchomości</label>
							<span class="wartosc wartosc-kwoty">{{ \App\Support\Format::cena($cenaAktualna) }}</span>
						</div>
						<input class="tor" type="range" id="cenaLokalu" name="kwota"
						       min="100000" max="{{ max(3700000, ceil($cenaAktualna)) }}" step="0.01" value="{{ number_format($cenaAktualna, 2, '.', '') }}"
						       aria-label="Cena nieruchomości w złotych">
					</div>
					<div class="suwak">
						<div class="opis">
							<label class="podpis" for="wkladWlasny">Wkład własny</label>
							<span class="wartosc wartosc-wkladu">{{ $wklad }}%</span>
						</div>
						<input class="tor" type="range" id="wkladWlasny" name="wklad"
						       min="0" max="90" step="1" value="{{ $wklad }}"
						       aria-label="Wkład własny w procentach ceny">
					</div>
					<div class="suwak">
						<div class="opis">
							<label class="podpis" for="okresSplaty">Okres spłaty</label>
							<span class="wartosc wartosc-okresu">{{ (int) $kalk->liczba('okres') }} lat</span>
						</div>
						<input class="tor" type="range" id="okresSplaty" name="okres"
						       min="1" max="35" step="1" value="{{ (int) $kalk->liczba('okres') }}"
						       aria-label="Okres spłaty w latach">
					</div>
					<div class="podsumowanie-raty">
						<span class="podpis">Szacunkowa rata:</span>
						<span class="wynik-raty" role="status">0,00 zł</span>
					</div>
					{{-- Założenia raty jawnie (uwaga klienta 27.10): od jakiej kwoty, jakie oprocentowanie, stan na kiedy --}}
					<p class="zalozenia-raty">
						Kredyt: <strong class="wartosc-kredytu"></strong> (cena minus wkład własny), raty równe,
						oprocentowanie {{ \App\Support\Format::procent($kalk->liczba('oprocentowanie')) }}@if($kalk->tekst('aktualizacja')) (stan na {{ $kalk->tekst('aktualizacja') }})@endif.
						{{ $kalk->tekst('zastrzezenie') }}
					</p>
					<p class="stopka-kalkulatora">
						@if($kalk->tekst('rrso'))
							RRSO <span class="akcent">{{ \App\Support\Format::procent($kalk->liczba('rrso')) }}</span>
						@endif
						<a href="{{ route('menu.show', ['uri' => 'finansowanie']) }}">Zobacz koszty kredytu</a>
					</p>
					<a class="przycisk-zapytaj" href="#kontakt">ZAPYTAJ O OFERTĘ</a>
				</section>
			</div>
			@endif
			@if($kafelWykonczenia)
			@php $przycisk = $kafelWykonczenia->link('przycisk'); $zdjecie = $kafelWykonczenia->obrazek('zdjecie'); @endphp
			<div class="col-12 col-md-6">
				<a class="kafel pojawia-sie opoznienie-1" href="{{ $przycisk->adres }}">
					<picture>
						<source type="image/webp" srcset="{{ $zdjecie->webp }}">
						<img src="{{ $zdjecie->jpg }}" width="830" height="482" alt="{{ $zdjecie->alt }}" loading="lazy">
					</picture>
					<x-etykieta>{{ $kafelWykonczenia->tekst('etykieta') }}</x-etykieta>
					<h2 class="tytul-sekcji rozjasnia-sie">{{ $kafelWykonczenia->tekst('naglowek') }}<br><span class="akcent">{{ $kafelWykonczenia->tekst('naglowek_akcent') }}</span></h2>
					<x-przycisk-pigulka>{{ $przycisk->tekst }}</x-przycisk-pigulka>
				</a>
			</div>
			@endif
		</div>
	</div>

	<x-sekcje.kontakt :strona="$lokal->nazwaPelna() . ' - ' . $inwestycja->name" :investment-id="$inwestycja->id" :property-id="$lokal->id" />
@endsection

@push('scripts')
	<script src="{{ zasob('js/kalkulator.js') }}"></script>
	<script src="{{ zasob('js/formularz.js') }}"></script>
@endpush
