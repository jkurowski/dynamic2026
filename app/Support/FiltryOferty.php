<?php

namespace App\Support;

use App\Models\Investment;
use App\Models\Property;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Filtry listy lokali: [pole => [etykieta, [opcje]]]. Pierwsza opcja = brak filtra.
 * Przedziały (pokoje, powierzchnia, cena) jak w szablonie - glowny.js rozumie zapisy „do 40 m²”,
 * „40 - 60 m²”, „powyżej 90 m²”, „4 i więcej”. Pola tekstowe (inwestycja, lokalizacja, rodzaj, status)
 * porównuje dosłownie z atrybutami data-* wiersza - dlatego te same napisy idą do opcji i do wierszy.
 */
class FiltryOferty
{
    private const POKOJE = ['Dowolna', '1', '2', '3', '4 i więcej'];
    private const POWIERZCHNIA = ['Dowolna', 'do 40 m²', '40 - 60 m²', '60 - 90 m²', 'powyżej 90 m²'];
    private const DZIALKA = ['Dowolna', 'do 300 m²', '300 - 500 m²', 'powyżej 500 m²'];
    private const PIETRO = ['Dowolne', 'Parter', '1', '2', '3 i wyżej'];
    private const STATUS = ['Dowolny', 'Dostępne', 'Rezerwacja', 'Sprzedane'];
    private const CENA = ['Dowolna', 'do 600 000 zł', '600 000 - 900 000 zł', '900 000 - 1 200 000 zł', 'powyżej 1 200 000 zł'];

    /** Podstrona inwestycji - zestaw z inwestycja.html (+ rodzaj, gdy w inwestycji jest więcej rodzajów) */
    public static function dlaInwestycji(Collection $lokale): array
    {
        $filtry = [];

        $rodzaje = self::rodzaje($lokale);
        if (count($rodzaje) > 1) {
            $filtry['rodzaj'] = ['Rodzaj', array_merge(['Dowolny'], $rodzaje)];
        }

        return $filtry + self::podstawowe($lokale);
    }

    /** Wybierz nieruchomość - dodatkowo inwestycja, lokalizacja i rodzaj (ustalenia z klientem 09-18.08) */
    public static function dlaWyszukiwarki(Collection $inwestycje): array
    {
        $lokale = $inwestycje->flatMap(fn (Investment $i) => $i->lokaleNaFroncie());

        return [
            'inwestycja' => ['Inwestycja', array_merge(['Wszystkie inwestycje'], $inwestycje->pluck('name')->all())],
            'lokalizacja' => ['Lokalizacja', array_merge(['Wszystkie lokalizacje'], $inwestycje->map(fn ($i) => self::lokalizacja($i))->filter()->unique()->values()->all())],
            'rodzaj' => ['Rodzaj', array_merge(['Dowolny'], self::rodzaje($lokale))],
        ] + self::podstawowe($lokale);
    }

    private static function podstawowe(Collection $lokale): array
    {
        $filtry = [];

        // „Liczba pokoi” nie dotyczy lokali usługowych
        if ($lokale->contains(fn (Property $l) => $l->pokazujPokoje())) {
            $filtry['pokoje'] = ['Liczba pokoi', self::POKOJE];
        }

        $filtry['powierzchnia'] = ['Powierzchnia', self::POWIERZCHNIA];

        // „Działka” tylko przy domach i segmentach
        if ($lokale->contains(fn (Property $l) => $l->pokazujDzialke())) {
            $filtry['dzialka'] = ['Działka', self::DZIALKA];
        }

        // „Piętro” tylko tam, gdzie ma zastosowanie (nie przy samych domach i segmentach)
        if ($lokale->contains(fn (Property $l) => $l->pokazujPietro())) {
            $filtry['pietro'] = ['Piętro', self::PIETRO];
        }

        return $filtry + [
            'status' => ['Status', self::STATUS],
            'cena' => ['Cena', self::CENA],
        ];
    }

    private static function rodzaje(Collection $lokale): array
    {
        return $lokale->map(fn (Property $l) => $l->nazwaTypu())->unique()->values()->all();
    }

    /** „WARSZAWA, MOKOTÓW” -> „Warszawa, Mokotów” (opcja filtra i atrybut wiersza) */
    public static function lokalizacja(Investment $inwestycja): ?string
    {
        return filled($inwestycja->location_label) ? Str::title(mb_strtolower($inwestycja->location_label)) : null;
    }
}
