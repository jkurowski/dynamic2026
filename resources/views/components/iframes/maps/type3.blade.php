{{-- Domy --}}
<map name="invesmentplan">
    @if ($investment->properties)
        @foreach ($investment->properties as $house)
            <area shape="poly" href="{{ route('front.iframe.single', [$investment->slug, $house->id]) }}"
                {{-- href="{{ $getRouteForProperty($investment, $house) }}" title="{{ $house->name }}" --}}
                alt="{{ $house->slug }}" data-item="{{ $house->id }}" data-roomnumber="{{ $house->number }}"
                data-roomtype="{{ $house->typ }}" data-roomstatus="{{ $house->status }}"
                coords="@if ($house->html) {{ cords($house->html) }} @endif">
        @endforeach
    @endif
</map>
