<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Property;
use Illuminate\Support\Collection;

/**
 * Schowek: porównanie zapisanych lokali i zbiorcze zapytanie o nie (wzorzec: schowek Kaltera).
 * Lista lokali jest w przeglądarce (localStorage, glowny.js) - przychodzi w adresie: /schowek?lokale=12,15,40.
 * Formularz pod porównaniem wysyła ContactController@send z polem `lokale`.
 */
class SchowekController extends Controller
{
    /** Tyle kolumn ma jeszcze sens w tabeli porównania */
    public const LIMIT = 10;

    public function index()
    {
        $lokale = self::lokale((string) request('lokale', ''));

        return view('front.schowek.index', [
            'lokale' => $lokale,
            'wiersze' => $this->wiersze($lokale),
        ]);
    }

    /**
     * Lokale z listy identyfikatorów („12,15” albo „lokal-12,lokal-15”) - tylko te, które widać na froncie
     * (inwestycja widoczna, lokal aktywny), w kolejności z listy.
     *
     * @return Collection<int, Property>
     */
    public static function lokale(string $lista): Collection
    {
        $id = collect(explode(',', $lista))
            ->map(fn ($wartosc) => (int) preg_replace('/\D/', '', $wartosc))
            ->filter()->unique()->take(self::LIMIT)->values();

        if ($id->isEmpty()) {
            return collect();
        }

        $widoczne = Investment::frontu()->flatMap(fn (Investment $inwestycja) => $inwestycja->lokaleNaFroncie())->keyBy('id');

        return $id->map(fn ($numer) => $widoczne->get($numer))->filter()->values();
    }

    /**
     * Wiersze tabeli porównania: [nazwa, wartości dla kolumn]. Wiersz, w którym żaden lokal nie ma wartości, pomijamy
     * (np. „Piętro” przy samych segmentach). `rozne` - wartości się różnią (wyróżnienie jak w Kalterze).
     */
    private function wiersze(Collection $lokale): array
    {
        $metraz = fn ($wartosc) => $wartosc > 0 ? \App\Support\Format::metraz($wartosc) : null;

        $definicje = [
            'Inwestycja' => fn (Property $l) => $l->investment->name,
            'Lokalizacja' => fn (Property $l) => \App\Support\FiltryOferty::lokalizacja($l->investment),
            'Rodzaj' => fn (Property $l) => $l->nazwaTypu(),
            'Powierzchnia' => fn (Property $l) => $l->metraz(),
            'Liczba pokoi' => fn (Property $l) => $l->pokazujPokoje() ? (string) $l->rooms : null,
            'Piętro' => fn (Property $l) => $l->pokazujPietro() ? $l->pietro() : null,
            'Działka' => fn (Property $l) => $l->pokazujDzialke() ? $l->dzialka() : null,
            'Ogród' => fn (Property $l) => $metraz($l->garden_area),
            'Taras' => fn (Property $l) => $metraz($l->terrace_area),
            'Balkon' => fn (Property $l) => $metraz((float) $l->balcony_area + (float) $l->balcony_area_2),
            'Loggia' => fn (Property $l) => $metraz($l->loggia_area),
            'Cena za m²' => fn (Property $l) => $l->cenaMetra(),
            'Termin oddania' => fn (Property $l) => $l->investment->date_end ?: null,
        ];

        $wiersze = [];
        foreach ($definicje as $nazwa => $wartosc) {
            $wartosci = $lokale->map($wartosc)->all();
            if (collect($wartosci)->filter()->isEmpty()) {
                continue;
            }
            $wiersze[] = [
                'nazwa' => $nazwa,
                'wartosci' => $wartosci,
                'rozne' => $lokale->count() > 1 && collect($wartosci)->unique()->count() > 1,
            ];
        }

        return $wiersze;
    }
}
