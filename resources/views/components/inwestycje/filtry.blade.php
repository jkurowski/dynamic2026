{{-- Filtry listy lokali - wygląd z szablonu (.filtry-inwestycji na podstronie inwestycji, .filtry-wynikow w wyszukiwarce).
     $filtry z App\Support\FiltryOferty: [pole => [etykieta, [opcje]]], pierwsza opcja = brak filtra.
     Filtrowanie bez przeładowania (glowny.js), wybór trafia do adresu (?pokoje=2...). --}}
@props(['filtry', 'klasa' => 'filtry-wynikow', 'widok' => false])
<form {{ $attributes->class([$klasa, 'filtry-szerokie' => count($filtry) > 6]) }} action="{{ url()->current() }}" method="get">
	@foreach($filtry as $pole => [$etykieta, $opcje])
		<button class="pole-filtru" type="button" data-bs-toggle="dropdown" data-pole="{{ $pole }}" data-etykieta="{{ $etykieta }}" aria-expanded="false">{{ $etykieta }}<span class="ikona-filtru" aria-hidden="true"></span></button>
		<ul class="dropdown-menu lista-filtru">
			@foreach($opcje as $opcja)
				<li><button @class(['dropdown-item', 'wybrany' => $loop->first]) type="button">{{ $opcja }}</button></li>
			@endforeach
		</ul>
	@endforeach
	<button class="przycisk-szukaj-pigulka" type="submit">SZUKAJ<img class="ikona-lupy" src="{{ asset('img/ikona-lupa.svg') }}" width="22" height="22" alt=""></button>
	@if($widok)
		<div class="widok">
			<span>Widok:</span>
			<button class="przelacznik-widoku" type="button" data-widok="lista" aria-label="Widok listy" aria-pressed="false"><img src="{{ asset('img/ikona-widok-lista.svg') }}" width="18" height="10" alt=""></button>
			<button class="przelacznik-widoku wybrany" type="button" data-widok="kafelki" aria-label="Widok kafelków" aria-pressed="true"><img src="{{ asset('img/ikona-widok-kafelki.svg') }}" width="15" height="15" alt=""></button>
		</div>
	@endif
</form>
