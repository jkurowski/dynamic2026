{{-- Bloki --}}
<map name="invesmentplan">
    @foreach ($investment->floors as $floor)
        @if ($floor->html)
            <area shape="poly" href="#" title="{{ $floor->name }}" alt="floor-{{ $floor->id }}"
                data-item="{{ $floor->id }}" data-floornumber="{{ $floor->id }}" data-floortype="{{ $floor->type }}"
                coords="{{ cords($floor->html) }}">
        @endif
    @endforeach
</map>
