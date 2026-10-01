{{-- Przycisk "pigułka" ze strzałką. Z href renderuje <a>, bez - <span> (np. wewnątrz klikalnego kafla). Dodatkowe klasy: na-tle. --}}
@props(['href' => null])
@if($href)
<a href="{{ $href }}" {{ $attributes->class(['przycisk-pigulka']) }}>
	<img src="{{ asset('img/ikona-strzalka-pigulka.svg') }}" width="16" height="30" alt="">
	{{ $slot }}
</a>
@else
<span {{ $attributes->class(['przycisk-pigulka']) }}>
	<img src="{{ asset('img/ikona-strzalka-pigulka.svg') }}" width="16" height="30" alt="">
	{{ $slot }}
</span>
@endif
