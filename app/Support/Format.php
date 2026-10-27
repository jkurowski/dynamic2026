<?php

namespace App\Support;

/** Liczby na froncie w zapisie z makiety */
class Format
{
    /** 1400573.3 -> „1.400.573,30 zł” (ceny lokali i „ceny od”) */
    public static function cena($kwota): string
    {
        return number_format((float) $kwota, 2, ',', '.') . ' zł';
    }

    /** 20627 -> „20 627,00 zł/m²” */
    public static function cenaMetra($kwota): string
    {
        return number_format((float) $kwota, 2, ',', ' ') . ' zł/m²';
    }

    /** Historia ceny: „599 875,20 zł” */
    public static function kwota($kwota): string
    {
        return number_format((float) $kwota, 2, ',', ' ') . ' zł';
    }

    /** 67.98 -> „67,98 m²”, 9.5 -> „9,50 m²” */
    public static function metraz($metry): string
    {
        return number_format((float) $metry, 2, ',', '') . ' m²';
    }

    /** Zakres metrażu: „34,46 - 67,98 m²” (bez m² w pierwszej liczbie) */
    public static function zakresMetrazu($od, $do): string
    {
        $od = number_format((float) $od, 2, ',', '');
        $do = number_format((float) $do, 2, ',', '');

        return $od === $do ? $od . ' m²' : $od . ' - ' . $do . ' m²';
    }

    /** 6.7 -> „6,7%”, 6.09 -> „6,09%” */
    public static function procent($wartosc): string
    {
        return rtrim(rtrim(number_format((float) $wartosc, 2, ',', ''), '0'), ',') . '%';
    }
}
