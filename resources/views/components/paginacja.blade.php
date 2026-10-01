{{-- Stronicowanie list (nav.paginacja z szablonu), adresy ?strona=N. Przyjmuje LengthAwarePaginator.
     Użycie: <x-paginacja :lista="$articles" etykieta="Stronicowanie listy wpisów" /> --}}
@props(['lista', 'etykieta' => 'Stronicowanie listy'])
@if($lista->hasPages())
	@php
		$biezaca = $lista->currentPage();
		$ostatnia = $lista->lastPage();
		// Numery: pierwsza, ostatnia i sąsiedzi bieżącej; luki jako "..."
		$numery = collect(range(1, $ostatnia))->filter(fn ($n) => $n <= 2 || $n > $ostatnia - 2 || abs($n - $biezaca) <= 1)->values();
	@endphp
	<nav {{ $attributes->class(['paginacja', 'pojawia-sie']) }} aria-label="{{ $etykieta }}">
		@if($biezaca > 1)
			<a class="poprzednia" href="{{ $lista->url($biezaca - 1) }}" aria-label="Poprzednia strona">
				<img src="{{ asset('img/ikona-strzalka-prawo.svg') }}" width="26" height="27" alt="">
			</a>
		@endif
		@foreach($numery as $i => $numer)
			@if($i > 0 && $numer - $numery[$i - 1] > 1)
				<span class="przerwa">...</span>
			@endif
			@if($numer == $biezaca)
				<a class="biezaca" href="{{ $lista->url($numer) }}" aria-current="page">{{ $numer }}</a>
			@else
				<a href="{{ $lista->url($numer) }}">{{ $numer }}</a>
			@endif
		@endforeach
		@if($biezaca < $ostatnia)
			<a class="nastepna" href="{{ $lista->url($biezaca + 1) }}" aria-label="Następna strona">
				<img src="{{ asset('img/ikona-strzalka-prawo.svg') }}" width="26" height="27" alt="">
			</a>
		@endif
	</nav>
@endif
