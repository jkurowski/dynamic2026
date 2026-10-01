@extends('layouts.iframe')
@section('content')
    @if ($custom_css)
        <style>
            {{ $custom_css }}
        </style>
    @endif

    @if ($investment->show_properties == 1)
        @if ($investment->plan)
            <div id="plan-holder">
                <div class="plan-holder-info">Z planu budynku wybierz piętro lub <a href="#filtr" class="scroll-link"
                        data-offset="90"><b>użyj wyszukiwarki</b></a></div>
                <img src="{{ asset('/investment/plan/' . $investment->plan->file) }}" alt="{{ $investment->name }}"
                    id="invesmentplan" usemap="#invesmentplan">

                <x-iframes.filters :$uniqueRooms :areaRange="$investment->area_range" />
                <x-iframes.sort />

                @switch($investment->type)
                    @case(2)
                        <x-iframes.maps.type2 :$investment />
                    @break

                    @case(3)
                        <x-iframes.maps.type3 :$investment />
                    @break

                    @default
                @endswitch
            </div>
        @endif
    @endif

    <x-iframes.properties-list :$investment :$properties />
@endsection
