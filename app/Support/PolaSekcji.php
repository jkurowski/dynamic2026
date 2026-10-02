<?php

namespace App\Support;

use App\Models\Sekcja;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Wartości pól sekcji do widoku: zapisane w panelu albo domyślne z configu (treść szablonu).
 * Ta sama klasa obsługuje całą sekcję i pojedynczy element listy (typ 'lista' - np. jedna liczba w „Liczbach”).
 */
class PolaSekcji
{
    /**
     * @param array $pola      definicje pól (config/sekcje.php -> 'pola' sekcji albo 'pola' listy)
     * @param array $dane      zapisane wartości
     * @param array $domyslne  [pole => wartość domyślna]
     */
    public function __construct(private array $pola, private array $dane, private array $domyslne)
    {
    }

    public static function dlaSekcji(array $pola, ?array $dane): self
    {
        return new self($pola, $dane ?? [], array_map(fn ($p) => $p['domyslnie'] ?? null, $pola));
    }

    public function definicja(string $pole): array
    {
        $definicja = $this->pola[$pole] ?? null;
        if (!$definicja) {
            throw new \InvalidArgumentException("Brak pola „{$pole}” w config/sekcje.php");
        }

        return $definicja;
    }

    /** Zapisana wartość albo domyślna (null/'' w zapisie = brak wartości) */
    public function wartosc(string $pole)
    {
        $this->definicja($pole);
        $zapisane = $this->dane[$pole] ?? null;

        return ($zapisane === null || $zapisane === '') ? ($this->domyslne[$pole] ?? null) : $zapisane;
    }

    public function tekst(string $pole): string
    {
        return (string) $this->wartosc($pole);
    }

    /** HTML z edytora - wpisuje go administrator, wypisujemy przez {!! !!} */
    public function html(string $pole): string
    {
        return (string) $this->wartosc($pole);
    }

    /** @return object{tekst: string, adres: string} */
    public function link(string $pole): object
    {
        $this->definicja($pole);
        $domyslnie = $this->domyslne[$pole] ?? [];
        $zapisane = $this->dane[$pole] ?? [];

        $tekst = ($zapisane['tekst'] ?? '') !== '' ? $zapisane['tekst'] : ($domyslnie['tekst'] ?? '');
        $adres = ($zapisane['adres'] ?? '') !== '' ? $zapisane['adres'] : ($domyslnie['adres'] ?? '');

        return (object) ['tekst' => $tekst, 'adres' => self::adres($adres)];
    }

    /** /poznaj-nas -> pełny adres strony; http(s), #, tel:, mailto: bez zmian */
    public static function adres(string $adres): string
    {
        return Str::startsWith($adres, '/') ? url($adres) : $adres;
    }

    /**
     * @return object{jpg: string, webp: string, alt: string, szerokosc: int, wysokosc: int, wlasny: bool}
     * wlasny = zdjęcie wgrane w panelu (przycięte do kadru), inaczej zdjęcie z szablonu
     */
    public function obrazek(string $pole): object
    {
        $definicja = $this->definicja($pole);
        $domyslnie = $this->domyslne[$pole] ?? [];
        $zapisane = $this->dane[$pole] ?? [];
        [$szerokosc, $wysokosc] = $definicja['rozmiar'] ?? $definicja['kadr'];

        $plik = $zapisane['plik'] ?? null;
        $wlasny = $plik && File::isFile(public_path(Sekcja::KATALOG . $plik));

        return (object) [
            'jpg' => asset($wlasny ? Sekcja::KATALOG . $plik : $domyslnie['jpg']),
            'webp' => asset($wlasny ? Sekcja::KATALOG . 'webp/' . pathinfo($plik, PATHINFO_FILENAME) . '.webp' : $domyslnie['webp']),
            'alt' => ($zapisane['alt'] ?? '') !== '' ? $zapisane['alt'] : ($domyslnie['alt'] ?? ''),
            'szerokosc' => $szerokosc,
            'wysokosc' => $wysokosc,
            'wlasny' => $wlasny,
        ];
    }

    /** Ikona wybrana z listy ikon szablonu ('opcje' => [plik => [nazwa, szer, wys]]) - @return object{src, szerokosc, wysokosc} */
    public function ikona(string $pole): object
    {
        $opcje = $this->definicja($pole)['opcje'];
        $plik = $this->wartosc($pole);
        if (!isset($opcje[$plik])) {
            $plik = $this->domyslne[$pole];
        }
        [, $szerokosc, $wysokosc] = $opcje[$plik];

        return (object) ['src' => asset($plik), 'szerokosc' => $szerokosc, 'wysokosc' => $wysokosc];
    }

    /**
     * Elementy listy o stałej liczbie (tyle, ile domyślnych w configu). Pole puste w zapisie -> wartość
     * domyślna elementu o tym samym numerze.
     *
     * @return self[]
     */
    public function lista(string $pole): array
    {
        $definicja = $this->definicja($pole);
        $zapisane = $this->dane[$pole] ?? [];

        return collect($definicja['domyslnie'])
            ->map(fn (array $domyslnyElement, int $i) => new self($definicja['pola'], $zapisane[$i] ?? [], $domyslnyElement))
            ->all();
    }
}
