{{-- Plan osiedla z zaznaczonym lokalem (uwaga klienta 11.11, zaakceptowana 13.11) - domy i segmenty.
     1) Inwestycja ma „Plan inwestycji” w panelu, a domy obrysy (pole HTML z <area coords> - edytor planu
        w formularzu domu) -> zdjęcie planu z obrysami w kolorach statusu; kadr przycięty do obrysów,
        żeby na telefonie domy nie były maleńkie.
     2) Inaczej schemat rysowany z numerów lokali (wiersz = budynek, np. 1A-1D, 2A-2D).
     Wybrany lokal wyróżniony, każdy dom prowadzi do swojej karty.
     Props: inwestycja, wybrany - lokal z karty lokalu (null na podstronie inwestycji). --}}
@props(['inwestycja', 'wybrany' => null])
@php
	$lokale = $inwestycja->lokaleNaFroncie()->filter(fn ($l) => $l->jestDomem());

	// 1) plan ze zdjęcia
	$obrysy = $lokale->mapWithKeys(function ($l) {
		if (!preg_match('/coords="([\d.,\s]+)"/', (string) $l->html, $m)) {
			return [];
		}
		$liczby = array_map('floatval', preg_split('/[\s,]+/', trim($m[1])));

		return count($liczby) >= 6 ? [$l->id => array_chunk($liczby, 2)] : [];
	});
	$planPlik = $inwestycja->plan?->file ? public_path('investment/plan/' . $inwestycja->plan->file) : null;
	$rozmiarPlanu = $planPlik && is_file($planPlik) && $obrysy->isNotEmpty() ? getimagesize($planPlik) : null;
	if ($rozmiarPlanu) {
		$punkty = $obrysy->flatten(1);
		$zapas = 20;
		$kadrX = max(0, $punkty->min(0) - $zapas);
		$kadrY = max(0, $punkty->min(1) - $zapas);
		$kadrW = min($rozmiarPlanu[0], $punkty->max(0) + $zapas) - $kadrX;
		$kadrH = min($rozmiarPlanu[1], $punkty->max(1) + $zapas) - $kadrY;
	}

	// 2) schemat: „1A” -> budynek „1”; numer bez cyfr na początku -> jeden wspólny rząd
	$budynki = $lokale->groupBy(fn ($l) => preg_match('/^\d+/', (string) $l->number, $m) ? $m[0] : '')->values();
	$szer = 150; $wys = 110; $odstep = 6; $rzad = 70; $margines = 40;
	$maksSegmentow = $budynki->map->count()->max() ?: 1;
	$szerokoscPlanu = $margines * 2 + $maksSegmentow * $szer + ($maksSegmentow - 1) * $odstep;
	$wysokoscPlanu = $margines * 2 + $budynki->count() * $wys + max(0, $budynki->count() - 1) * $rzad;
@endphp
@if($lokale->isNotEmpty())
<section class="plan-osiedla pojawia-sie" id="plan-osiedla" aria-labelledby="plan-osiedla-tytul">
	<x-etykieta>PLAN OSIEDLA</x-etykieta>
	<h2 class="tytul-sekcji rozjasnia-sie" id="plan-osiedla-tytul">
		@if($wybrany)
			{{ $wybrany->nazwaPelna() }} <span class="akcent">na planie osiedla</span>
		@else
			Wybierz {{ $inwestycja->produkt() }} <span class="akcent">na planie</span>
		@endif
	</h2>

	<div class="plan-osiedla-ramka {{ $rozmiarPlanu ? 'plan-ze-zdjecia' : '' }}">
		@if($rozmiarPlanu)
			{{-- na telefonie plan szerszy od ekranu - przewijany w poziomie (inaczej numery domów po ~6 px) --}}
			<div class="plan-przewijany" tabindex="0" aria-label="Plan osiedla - przewiń w poziomie">
			<svg viewBox="{{ $kadrX }} {{ $kadrY }} {{ $kadrW }} {{ $kadrH }}" role="img" aria-label="Plan osiedla {{ $inwestycja->name }}">
				<image href="{{ asset('investment/plan/' . $inwestycja->plan->file) }}" x="0" y="0" width="{{ $rozmiarPlanu[0] }}" height="{{ $rozmiarPlanu[1] }}"/>
				@foreach($lokale->filter(fn ($l) => $obrysy->has($l->id)) as $segment)
					@php
						$obrys = $obrysy[$segment->id];
						$wybranyTen = $wybrany && $wybrany->id === $segment->id;
						$srodekX = array_sum(array_column($obrys, 0)) / count($obrys);
						$srodekY = array_sum(array_column($obrys, 1)) / count($obrys);
					@endphp
					<a href="{{ $segment->link() }}" @if($wybranyTen) aria-current="page" @endif>
						<title>{{ $segment->nazwaPelna() }} - {{ $segment->statusNapis() }}</title>
						<polygon class="segment-planu {{ $segment->statusKlasa() }} {{ $wybranyTen ? 'wybrany' : '' }}" points="{{ collect($obrys)->map(fn ($p) => $p[0] . ',' . $p[1])->implode(' ') }}"/>
						<text x="{{ round($srodekX, 1) }}" y="{{ round($srodekY + 4, 1) }}" text-anchor="middle">{{ $segment->number }}</text>
					</a>
				@endforeach
			</svg>
			</div>
			<p class="podpowiedz-planu">Przesuń plan w bok, aby zobaczyć wszystkie domy.</p>
		@else
			<svg viewBox="0 0 {{ $szerokoscPlanu }} {{ $wysokoscPlanu }}" role="img" aria-label="Schemat osiedla {{ $inwestycja->name }}">
				@foreach($budynki as $nrBudynku => $segmenty)
					@php $y = $margines + $nrBudynku * ($wys + $rzad); @endphp
					@foreach($segmenty as $segment)
						@php $x = $margines + $loop->index * ($szer + $odstep); $wybranyTen = $wybrany && $wybrany->id === $segment->id; @endphp
						<a href="{{ $segment->link() }}" @if($wybranyTen) aria-current="page" @endif>
							<title>{{ $segment->nazwaPelna() }} - {{ $segment->statusNapis() }}</title>
							<rect class="segment-planu {{ $segment->statusKlasa() }} {{ $wybranyTen ? 'wybrany' : '' }}" x="{{ $x }}" y="{{ $y }}" width="{{ $szer }}" height="{{ $wys }}" rx="6"/>
							<text x="{{ $x + $szer / 2 }}" y="{{ $y + $wys / 2 + 8 }}" text-anchor="middle">{{ $segment->number }}</text>
						</a>
					@endforeach
					@if(!$loop->last)
						{{-- droga wewnętrzna między rzędami --}}
						<rect class="droga-planu" x="{{ $margines / 2 }}" y="{{ $y + $wys + $rzad / 2 - 9 }}" width="{{ $szerokoscPlanu - $margines }}" height="18" rx="9"/>
					@endif
				@endforeach
			</svg>
		@endif

		<ul class="legenda-planu">
			@if($wybrany)<li><span class="znacznik wybrany"></span> wybrany {{ mb_strtolower($wybrany->nazwaTypu()) }}</li>@endif
			<li><span class="znacznik dostepne"></span> dostępny</li>
			<li><span class="znacznik rezerwacja"></span> rezerwacja</li>
			<li><span class="znacznik sprzedane"></span> sprzedany</li>
		</ul>
		@unless($rozmiarPlanu)
			<p class="uwaga-planu">Schemat poglądowy - plan osiedla w przygotowaniu.</p>
		@endunless
	</div>
</section>
@endif
