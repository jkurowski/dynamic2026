<?php

if (!function_exists('zasob')) {
    /** asset() z ?v=czas zmiany pliku - po wdrożeniu przeglądarka nie bierze starego CSS/JS z pamięci */
    function zasob(string $sciezka): string
    {
        $plik = public_path($sciezka);

        return asset($sciezka) . (is_file($plik) ? '?v=' . filemtime($plik) : '');
    }
}
