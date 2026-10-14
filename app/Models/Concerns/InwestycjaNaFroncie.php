<?php

namespace App\Models\Concerns;

use App\Models\Property;
use App\Support\Format;
use App\Support\MapaStatyczna;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

/**
 * Inwestycja na nowym froncie: lista /inwestycje, podstrona, mapa i sekcja na stronie głównej,
 * wyszukiwarka /wybierz-nieruchomosc. Status: 1 w sprzedaży, 2 zrealizowana, 3 w realizacji (planowana),
 * 4 ukryta - nie ma jej nigdzie na froncie (np. Falenty przygotowywane przed startem sprzedaży).
 */
trait InwestycjaNaFroncie
{
    /** status => [klasa na mapie, plakietka] */
    public static array $STATUSY_FRONTU = [
        1 => ['sprzedaz', 'W SPRZEDAŻY'],
        2 => ['zrealizowane', 'ZREALIZOWANA'],
        3 => ['realizacja', 'W REALIZACJI'],
    ];

    /** Rodzaje lokali, które sprzedajemy (bez komórek i miejsc postojowych) */
    public static array $TYPY_OFERTY = [Property::TYP_MIESZKANIE, Property::TYP_LOKAL_USLUGOWY, Property::TYP_DOM, Property::TYP_SEGMENT];

    public const KATALOG_KART = 'investment/thumbs/';

    /** Inwestycje widoczne na froncie (menu, stopka, strona główna) - raz na żądanie */
    public static function frontu(): Collection
    {
        if (!app()->bound('inwestycje.frontu')) {
            app()->instance('inwestycje.frontu', static::naFroncie()->with('city')->get());
        }

        return app('inwestycje.frontu');
    }

    /** Adres z menu/stopki: podstrona, strona zewnętrzna albo karta na liście /inwestycje#slug */
    public function linkMenu(): string
    {
        return $this->link() ?? route('inwestycje.index') . '#' . $this->slug;
    }

    public function scopeNaFroncie(Builder $query): Builder
    {
        return $query->whereIn('status', array_keys(self::$STATUSY_FRONTU))->orderBy('sort')->orderBy('id');
    }

    public function scopeWSprzedazy(Builder $query): Builder
    {
        return $query->where('status', 1)->orderBy('sort')->orderBy('id');
    }

    /**
     * Podstrona u nas: inwestycja bez adresu zewnętrznego, która ma lokale w panelu.
     * Z adresem zewnętrznym karta prowadzi na stronę inwestycji, bez obu - nie prowadzi nigdzie („WKRÓTCE”).
     */
    public function maPodstrone(): bool
    {
        return blank($this->external_url) && $this->lokaleNaFroncie()->isNotEmpty();
    }

    public function link(): ?string
    {
        if ($this->maPodstrone()) {
            return route('inwestycje.show', $this->slug);
        }

        return filled($this->external_url) ? $this->external_url : null;
    }

    public function linkZewnetrzny(): bool
    {
        return !$this->maPodstrone() && filled($this->external_url);
    }

    public function plakietka(): string
    {
        return self::$STATUSY_FRONTU[$this->status][1] ?? '';
    }

    public function statusNaMapie(): string
    {
        return self::$STATUSY_FRONTU[$this->status][0] ?? 'sprzedaz';
    }

    /** Zdjęcie karty (lista, strona główna). Bez zdjęcia - wizualizacja z szablonu. */
    public function zdjecieKarty(bool $webp = false): string
    {
        if ($this->file_thumb && File::isFile(public_path(self::KATALOG_KART . $this->file_thumb))) {
            $plikWebp = self::KATALOG_KART . 'webp/' . pathinfo($this->file_thumb, PATHINFO_FILENAME) . '.webp';
            if ($webp && File::isFile(public_path($plikWebp))) {
                return asset($plikWebp);
            }

            return asset(self::KATALOG_KART . $this->file_thumb);
        }

        return asset($webp ? 'img/lista-hygge.webp' : 'img/lista-hygge.jpg');
    }

    /** Zdjęcie w nagłówku podstrony (1019x650) - z panelu, inaczej zdjęcie karty */
    public function zdjecieNaglowka(bool $webp = false): string
    {
        if ($this->file_header && File::isFile(public_path('investment/header/' . $this->file_header))) {
            $plikWebp = 'investment/header/webp/' . pathinfo($this->file_header, PATHINFO_FILENAME) . '.webp';
            if ($webp && File::isFile(public_path($plikWebp))) {
                return asset($plikWebp);
            }

            return asset('investment/header/' . $this->file_header);
        }

        return $this->zdjecieKarty($webp);
    }

    /**
     * Produkt inwestycji do napisów („Znajdź mieszkanie”, „Znajdź dom”): najczęstszy rodzaj lokali
     * bez lokali usługowych. Klient (09.08): nie „mieszkanie” przy domach i segmentach.
     */
    public function produkt(): string
    {
        $rodzaj = $this->lokaleNaFroncie()
            ->where('type', '!=', Property::TYP_LOKAL_USLUGOWY)
            ->countBy('type')->sortDesc()->keys()->first();

        return mb_strtolower(Property::$NAZWY_TYPOW[$rodzaj] ?? 'Nieruchomość');
    }

    /** Lokale na froncie (dostępne, zarezerwowane, sprzedane), w kolejności z panelu - raz na żądanie */
    public function lokaleNaFroncie(): Collection
    {
        if (!$this->relationLoaded('lokaleFrontu')) {
            $this->setRelation('lokaleFrontu', $this->properties()
                ->with('floor', 'investment')
                ->where('active', 1)
                ->whereIn('type', self::$TYPY_OFERTY)
                ->whereIn('status', [1, 2, 3])
                ->orderBy('number_order')->orderBy('id')
                ->get());
        }

        return $this->getRelation('lokaleFrontu');
    }

    public function liczbaDostepnych(): int
    {
        return $this->lokaleNaFroncie()->where('status', 1)->count();
    }

    /** „805.516,00 zł” - najtańszy dostępny lokal z ceną; null, gdy brak */
    public function cenaOd(): ?string
    {
        $cena = $this->lokaleNaFroncie()->where('status', 1)->where('price_brutto', '>', 0)->min('price_brutto');

        return $cena ? Format::cena($cena) : null;
    }

    /** Zakres metrażu z lokali (mieszkania, domy, segmenty), inaczej wpisany w panelu */
    public function zakresMetrazu(): ?string
    {
        $metraze = $this->lokaleNaFroncie()
            ->whereIn('type', [Property::TYP_MIESZKANIE, Property::TYP_DOM, Property::TYP_SEGMENT])
            ->whereIn('status', [1, 2])
            ->pluck('area')->filter();

        if ($metraze->isNotEmpty()) {
            return Format::zakresMetrazu($metraze->min(), $metraze->max());
        }

        return filled($this->area_range) ? $this->area_range : null;
    }

    public function terminOddania(): ?string
    {
        return filled($this->date_end) ? $this->date_end : null;
    }

    /** Pierwsza linia adresu do paska na zdjęciu i dymka: „ul. Bobrowiecka 4B, Warszawa” */
    public function adresPelny(): string
    {
        return collect([$this->address, optional($this->city)->name])->filter()->implode(', ');
    }

    public function pozycjaNaMapie(): ?array
    {
        return MapaStatyczna::pozycja($this->lat, $this->lng);
    }

    /** Link do nawigacji Google Maps - adres albo współrzędne */
    public function jakDojechac(): string
    {
        $cel = filled($this->address) ? $this->adresPelny() : trim($this->lat . ',' . $this->lng, ',');

        return 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($cel);
    }
}
