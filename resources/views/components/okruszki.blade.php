{{-- Ścieżka nawigacji podstron. Pierwsza pozycja "Strona główna" dokładana automatycznie.
     Użycie: <x-okruszki :sciezka="['Inwestycje' => route('menu.show', ['uri' => 'inwestycje']), 'Dom Hygge Twin' => null]" /> - ostatnia pozycja bez linku. --}}
@props(['sciezka' => []])
@php
	$pozycje = ['<a href="' . e(route('index')) . '">Strona główna</a>'];
	foreach ($sciezka as $nazwa => $link) {
		$pozycje[] = $link ? '<a href="' . e($link) . '">' . e($nazwa) . '</a>' : '<span>' . e($nazwa) . '</span>';
	}
@endphp
<nav {{ $attributes->class(['okruszki']) }} aria-label="Ścieżka nawigacji">
	{!! implode('<span class="rozdzielacz">/</span>', $pozycje) !!}
</nav>
