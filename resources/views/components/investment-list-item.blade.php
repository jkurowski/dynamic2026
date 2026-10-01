@props([
    'investment',
    'properties',
    'sort' => null
])
@if($properties->count() > 0)
<div class="col-12">
    <div class="investment-list-item">
        <div class="position-relative @if(!$investment->file_header) investment-list-noimage @endif investment-header">
            @if($investment->file_header)
                <img src="{{ asset('investment/header/'.$investment->file_header) }}" alt="{{ $investment->name }}" loading="eager" decoding="async" class="w-100 position-absolute bottom-0">
            @endif
            <div class="investment-apla"></div>
            <div class="investment-list-desc">
                <h2>{{ $investment->name }}</h2>
                <p>{{ $investment->city->name }}</p>
                <span>{!! investmentAdvanced($investment->progress) !!}</span>
            </div>
        </div>

        <div id="layout-container" class="list-layout">
        @foreach ($properties as $index => $property)
            @if($investment->type == 1)
                @if(optional($property->building)->active == 1)
                    <x-property-new-list-item :p="$property" :investment="$investment" :index="$index" :sort="$sort"/>
                @endif
            @else
                <x-property-new-list-item :p="$property" :investment="$investment" :index="$index" :sort="$sort"/>
            @endif
        @endforeach
        </div>
    </div>
</div>
@endif
