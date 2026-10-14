<?php

namespace App\Support;

/**
 * Mapa w szablonie (img/mapa.jpg) to statyczny obrazek - pinezki stoją w procentach szerokości/wysokości.
 * Przeliczenie liniowe lat/lng <-> %, skalibrowane na dwóch punktach z config/mapa.php.
 * Używają: biura (City) na stronie Kontakt i inwestycje (Investment) na mapie na stronie głównej.
 */
class MapaStatyczna
{
    /** @return array{left: float, top: float}|null  null - brak współrzędnych albo punkt poza obrazkiem */
    public static function pozycja($lat, $lng): ?array
    {
        if (!is_numeric($lat) || !is_numeric($lng)) {
            return null;
        }

        [[$lat1, $lng1, $x1, $y1], [$lat2, $lng2, $x2, $y2]] = config('mapa.kalibracja');

        $left = $x1 + ($lng - $lng1) * ($x2 - $x1) / ($lng2 - $lng1);
        $top = $y1 + ($lat - $lat1) * ($y2 - $y1) / ($lat2 - $lat1);

        if ($left < 0 || $left > 100 || $top < 0 || $top > 100) {
            return null;
        }

        return ['left' => round($left, 1), 'top' => round($top, 1)];
    }

    /** Odwrotnie: % na obrazku -> lat/lng (np. pinezki narysowane w makiecie) */
    public static function wspolrzedne(float $left, float $top): array
    {
        [[$lat1, $lng1, $x1, $y1], [$lat2, $lng2, $x2, $y2]] = config('mapa.kalibracja');

        return [
            'lat' => round($lat1 + ($top - $y1) * ($lat2 - $lat1) / ($y2 - $y1), 7),
            'lng' => round($lng1 + ($left - $x1) * ($lng2 - $lng1) / ($x2 - $x1), 7),
        ];
    }
}
