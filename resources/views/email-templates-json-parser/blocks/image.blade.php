
<div style="{{ $style }}">
    @if (isset($props['linkHref']))
        <a href="{{ $props['linkHref'] ?? '' }}" style="text-decoration:none" target="_blank">
    @endif

    <img src="{{ $props['url'] ?? '' }}"
        {{ isset($props['width']) && $props['width'] ? 'width="' . $props['width'] . '"' : '' }}
        {{ isset($props['height']) && $props['height'] ? 'height="' . $props['height'] . '"' : '' }}
        alt="{{ $props['alt'] ?? '' }}"
        style="{{ isset($props['height']) && $props['height'] ? 'height:' . $props['height'] . ';' : '' }}{{ isset($props['width']) && $props['width'] ? 'width:' . $props['width'] . ';' : '' }}outline:none;border:none;text-decoration:none;vertical-align:middle;display:inline-block;max-width:100%" />

    @if (isset($props['linkHref']))
        </a>
    @endif
</div>
