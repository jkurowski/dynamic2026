<div style="{{ $style }}">
    <img alt="{{ $props['alt'] ?? '' }}" src="{{ $props['imageUrl'] }}" height="{{ $props['size'] ?? 64 }}"
        width="{{ $props['size'] ?? 64 }}"
        style="outline: none; border: none; text-decoration: none; object-fit: cover; height: {{ isset($props['size']) ? $props['size'] . 'px' : "64px" }}; width: {{ isset($props['size']) ? $props['size'] . 'px' : "64px" }}; max-width: 100%; display: inline-block; vertical-align: middle; text-align: center; {{ $shapeStyles }}">
</div>
