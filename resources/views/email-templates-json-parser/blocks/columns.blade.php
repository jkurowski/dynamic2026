<style>
    .columns-container {
        display: flex;
        width: 100%;
        flex-wrap: wrap;
        row-gap: 1rem;
        gap: 1rem;
    }

    .columns-container>.column {
        min-width: 300px;
        padding: 0 !important;
    }
</style>

<div style="{{ $style }}" class='columns-container'>
    @foreach ($renderedColumns as $index => $columnContent)
        @if ($columnContent != '')
            <div class='column'
                style="box-sizing: border-box; {{ $columnVerticalAlign ?? '' }}{{ $columnsGap[$index] ?? '' }} {{ isset($props['fixedWidths'][$index]) ? 'width:' . $props['fixedWidths'][$index] . 'px;' : 'flex: 1;' }}">
                {!! $columnContent !!}
            </div>
        @endif
    @endforeach
</div>
