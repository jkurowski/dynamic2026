<?php

namespace App\Models\Concerns;

use App\Support\Format;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Lokal (Property) na nowym froncie: karty i wiersze na podstronie inwestycji i w wyszukiwarce, karta lokalu.
 * Nazwa produktu zależy od rodzaju (mieszkanie, lokal usługowy, dom, segment), piętro tylko tam, gdzie ma sens.
 */
trait LokalNaFroncie
{
    public const TYP_MIESZKANIE = 1;
    public const TYP_LOKAL_USLUGOWY = 5;
    public const TYP_DOM = 7;
    public const TYP_SEGMENT = 8;

    public static array $NAZWY_TYPOW = [
        self::TYP_MIESZKANIE => 'Mieszkanie',
        self::TYP_LOKAL_USLUGOWY => 'Lokal usługowy',
        self::TYP_DOM => 'Dom',
        self::TYP_SEGMENT => 'Segment',
    ];

    /** status => [klasa CSS z szablonu, napis] */
    public static array $STATUSY_LOKALU = [
        1 => ['dostepne', 'DOSTĘPNE'],
        2 => ['rezerwacja', 'REZERWACJA'],
        3 => ['sprzedane', 'SPRZEDANE'],
    ];

    public const KATALOG_RZUTOW = 'investment/property/';

    public function nazwaTypu(): string
    {
        return self::$NAZWY_TYPOW[$this->type] ?? 'Lokal';
    }

    /** „C.1” - numer z panelu, inaczej nazwa */
    public function numerLokalu(): string
    {
        return (string) ($this->number ?: $this->name);
    }

    /** „Mieszkanie C.1” */
    public function nazwaPelna(): string
    {
        return $this->nazwaTypu() . ' ' . $this->numerLokalu();
    }

    /** Adres karty lokalu: /inwestycje/dom-hygge-twin/12-mieszkanie-c-1 */
    public function link(): string
    {
        return route('inwestycje.lokal', [$this->investment->slug, $this->id . '-' . Str::slug($this->nazwaPelna())]);
    }

    public function statusKlasa(): string
    {
        return self::$STATUSY_LOKALU[$this->status][0] ?? 'dostepne';
    }

    public function statusNapis(): string
    {
        return self::$STATUSY_LOKALU[$this->status][1] ?? '';
    }

    public function sprzedany(): bool
    {
        return (int) $this->status === 3;
    }

    /** Piętro dotyczy mieszkań i lokali w budynku - przy domach i segmentach go nie pokazujemy */
    public function pokazujPietro(): bool
    {
        return in_array((int) $this->type, [self::TYP_MIESZKANIE, self::TYP_LOKAL_USLUGOWY], true) && $this->floor;
    }

    /** „parter”, „1”, „2”... */
    public function pietro(): string
    {
        $numer = (int) optional($this->floor)->number;

        return $numer === 0 ? 'parter' : (string) $numer;
    }

    public function jestDomem(): bool
    {
        return in_array((int) $this->type, [self::TYP_DOM, self::TYP_SEGMENT], true);
    }

    /** Działka - parametr domów i segmentów (zamiast piętra) */
    public function pokazujDzialke(): bool
    {
        return $this->jestDomem() && $this->plot_area > 0;
    }

    public function dzialka(): string
    {
        return Format::metraz($this->plot_area);
    }

    public function pokazujPokoje(): bool
    {
        return (int) $this->type !== self::TYP_LOKAL_USLUGOWY && $this->rooms > 0;
    }

    public function maCene(): bool
    {
        return !$this->sprzedany() && $this->price_brutto > 0;
    }

    public function cena(): string
    {
        return Format::cena($this->price_brutto);
    }

    public function cenaMetra(): ?string
    {
        return $this->maCene() && $this->area > 0 ? Format::cenaMetra($this->price_brutto / $this->area) : null;
    }

    public function metraz(): string
    {
        return Format::metraz($this->area);
    }

    /** Rzut: '' - karta lokalu, 'thumbs' - kafelki, 'list' - lista. Bez rzutu - rzut z szablonu. */
    public function rzut(string $rozmiar = '', bool $webp = false): string
    {
        $katalog = self::KATALOG_RZUTOW . ($rozmiar ? $rozmiar . '/' : '');

        if ($webp && $this->file_webp && File::isFile(public_path($katalog . 'webp/' . $this->file_webp))) {
            return asset($katalog . 'webp/' . $this->file_webp);
        }
        if ($this->file && File::isFile(public_path($katalog . $this->file))) {
            return asset($katalog . $this->file);
        }

        return asset($webp ? 'img/lokal-rzut.webp' : 'img/lokal-rzut.jpg');
    }

    public function kartaPdf(): ?string
    {
        return $this->file_pdf && File::isFile(public_path(self::KATALOG_RZUTOW . 'pdf/' . $this->file_pdf))
            ? asset(self::KATALOG_RZUTOW . 'pdf/' . $this->file_pdf)
            : null;
    }

    /**
     * Przycisk akcji zależny od statusu konkretnego lokalu (ustalenie z klientem 18.08):
     * sprzedany - „Zapytaj o podobne”, bez ceny - „Zapytaj o cenę”, inaczej „Oblicz ratę” (kalkulator na karcie lokalu).
     * @return array{0: string, 1: string} [napis, adres]
     */
    public function akcja(): array
    {
        if ($this->sprzedany()) {
            return ['ZAPYTAJ O PODOBNE', $this->link() . '#kontakt'];
        }
        if (!$this->maCene()) {
            return ['ZAPYTAJ O CENĘ', $this->link() . '#kontakt'];
        }

        return ['OBLICZ RATĘ', $this->link() . '#kalkulatorRaty'];
    }

    /**
     * Dane dla filtrów i sortowania w przeglądarce (glowny.js) - atrybuty data-* karty/wiersza.
     * Teksty jak w opcjach filtrów (FiltryOferty), liczby z kropką.
     */
    public function daneFiltra(): array
    {
        return [
            'powierzchnia' => (float) $this->area,
            'pokoje' => $this->pokazujPokoje() ? (int) $this->rooms : '',
            'pietro' => $this->pokazujPietro() ? (int) optional($this->floor)->number : '',
            'dzialka' => $this->pokazujDzialke() ? (float) $this->plot_area : '',
            'cena' => $this->maCene() ? (float) $this->price_brutto : '',
            'status' => mb_strtolower($this->statusNapis()),
            'rodzaj' => $this->nazwaTypu(),
            'inwestycja' => optional($this->investment)->name,
            'lokalizacja' => $this->investment ? \App\Support\FiltryOferty::lokalizacja($this->investment) : '',
        ];
    }

    /** Identyfikator w schowku (localStorage) - ten sam na liście, w wyszukiwarce i na karcie lokalu */
    public function idUlubionych(): string
    {
        return 'lokal-' . $this->id;
    }

    /**
     * Balkony, taras, loggia, ogród - [ikona, nazwa, wartość]. Pola z panelu, puste pomijamy.
     * Numer przy balkonie tylko, gdy są dwa.
     */
    public function cechy(): array
    {
        $cechy = [];
        $dwaBalkony = $this->balcony_area > 0 && $this->balcony_area_2 > 0;

        if ($this->kitchen) {
            $cechy[] = ['lokal-ikona-aneks.svg', 'Aneks/Kuchnia', kitchenType((int) $this->kitchen) ?: $this->kitchen];
        }
        if ($wystawa = getWindowDirections($this->window)) {
            $cechy[] = ['lokal-ikona-okno.svg', 'Wystawa okienna', $wystawa];
        }
        if ($this->balcony_area > 0) {
            $cechy[] = ['lokal-ikona-balkon.svg', $dwaBalkony ? 'Balkon 1' : 'Balkon', Format::metraz($this->balcony_area)];
        }
        if ($this->balcony_area_2 > 0) {
            $cechy[] = ['lokal-ikona-balkon.svg', 'Balkon 2', Format::metraz($this->balcony_area_2)];
        }
        if ($this->terrace_area > 0) {
            $cechy[] = ['lokal-ikona-balkon.svg', 'Taras', Format::metraz($this->terrace_area)];
        }
        if ($this->loggia_area > 0) {
            $cechy[] = ['lokal-ikona-balkon.svg', 'Loggia', Format::metraz($this->loggia_area)];
        }
        if ($this->garden_area > 0) {
            $cechy[] = ['lokal-ikona-balkon.svg', 'Ogród', Format::metraz($this->garden_area)];
        }
        // przy domu i segmencie działka jest w parametrach u góry karty
        if ($this->plot_area > 0 && !$this->pokazujDzialke()) {
            $cechy[] = ['lokal-ikona-metraz.svg', 'Działka', Format::metraz($this->plot_area)];
        }

        return $cechy;
    }
}
