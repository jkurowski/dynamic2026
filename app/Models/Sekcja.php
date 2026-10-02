<?php

namespace App\Models;

use App\Support\PolaSekcji;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Edytowalna stała sekcja frontu (np. strona główna - O nas). Pola i wartości domyślne: config/sekcje.php,
 * tu tylko to, co zapisano w panelu (`dane` - JSON w kolumnie TEXT). W widoku: sekcja('klucz').
 */
class Sekcja extends Model
{
    use LogsActivity;

    public const KATALOG = 'uploads/sekcje/';

    protected $table = 'sekcje';

    protected $fillable = ['klucz', 'dane'];

    // Cast 'array' koduje do zwykłego tekstu - kolumna jest TEXT, nie JSON
    protected $casts = ['dane' => 'array'];

    private ?PolaSekcji $wartosci = null;

    protected static function booted()
    {
        // Po zapisie wartości do widoku liczymy od nowa
        static::saved(fn (Sekcja $sekcja) => $sekcja->wartosci = null);
    }

    /** Sekcja do widoku - jedno zapytanie na klucz w obrębie żądania */
    public static function dlaKlucza(string $klucz): self
    {
        $cache = 'sekcja.' . $klucz;
        if (app()->bound($cache)) {
            return app($cache);
        }

        if (!self::schematDla($klucz)) {
            throw new \InvalidArgumentException("Brak sekcji „{$klucz}” w config/sekcje.php");
        }

        $sekcja = self::firstOrNew(['klucz' => $klucz]);
        app()->instance($cache, $sekcja);

        return $sekcja;
    }

    /** config('sekcje.x.y') nie zadziała - klucze sekcji zawierają kropki */
    public static function schematDla(string $klucz): ?array
    {
        return config('sekcje')[$klucz] ?? null;
    }

    public function schemat(): array
    {
        return self::schematDla($this->klucz) ?? ['nazwa' => $this->klucz, 'pola' => []];
    }

    public function nazwa(): string
    {
        return $this->schemat()['nazwa'] ?? $this->klucz;
    }

    /** Wartości pól do widoku (zapisane albo z szablonu) */
    public function wartosci(): PolaSekcji
    {
        return $this->wartosci ??= PolaSekcji::dlaSekcji($this->schemat()['pola'], $this->dane);
    }

    // Skróty do widoku: $s->tekst('naglowek'), $s->lista('liczby') itd.
    public function tekst(string $pole): string { return $this->wartosci()->tekst($pole); }
    public function html(string $pole): string { return $this->wartosci()->html($pole); }
    public function link(string $pole): object { return $this->wartosci()->link($pole); }
    public function obrazek(string $pole): object { return $this->wartosci()->obrazek($pole); }
    public function ikona(string $pole): object { return $this->wartosci()->ikona($pole); }
    /** @return PolaSekcji[] */
    public function lista(string $pole): array { return $this->wartosci()->lista($pole); }

    /** Atrybut dla edytora na froncie - tylko dla zalogowanych z uprawnieniem, gość dostaje pusty tekst */
    public function edycja(): string
    {
        return self::moznaEdytowac() ? ' data-sekcja="' . e($this->klucz) . '" data-sekcja-nazwa="' . e($this->nazwa()) . '"' : '';
    }

    public static function moznaEdytowac(): bool
    {
        $uzytkownik = auth('web')->user();

        return $uzytkownik && $uzytkownik->can('sekcja-edit');
    }

    /** Pliki zdjęcia wgranego do pola (JPG + WebP) */
    public static function usunPlik(?string $plik): void
    {
        if (!$plik) {
            return;
        }

        foreach ([self::KATALOG . $plik, self::KATALOG . 'webp/' . pathinfo($plik, PATHINFO_FILENAME) . '.webp'] as $sciezka) {
            if (File::isFile(public_path($sciezka))) {
                File::delete(public_path($sciezka));
            }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Sekcje stron')
            ->logOnly(['dane'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created', 'updated' => 'Zmieniono sekcję: ' . $this->nazwa(),
                'deleted' => 'Przywrócono treść z szablonu: ' . $this->nazwa(),
                default => $eventName,
            });
    }

    /**
     * Zamiast całego JSON-a w dzienniku - tylko zmienione pola, z etykietami z configu
     * (kolumna „Co się zmieniło” w admin/logs pokazuje pary etykieta: stare → nowe).
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        // Spatie woła tapActivity dwa razy (przy zdarzeniu modelu i w log()) - drugi raz dane są już rozpisane
        if (!$activity->properties->has('attributes') || !array_key_exists('dane', (array) $activity->properties->get('attributes'))) {
            return;
        }

        // Spatie zapisuje kolumnę z castem 'array' w surowej postaci (tekst JSON)
        $dekoduj = fn ($v) => is_string($v) ? (json_decode($v, true) ?: []) : ($v ?? []);
        $stare = $dekoduj(data_get($activity->properties, 'old.dane'));
        $nowe = $dekoduj(data_get($activity->properties, 'attributes.dane'));
        $przed = $this->splaszcz($this->schemat()['pola'], $stare);
        $po = $this->splaszcz($this->schemat()['pola'], $nowe);
        $old = $attributes = [];

        foreach ($po as $etykieta => $wartosc) {
            if (($przed[$etykieta] ?? null) !== $wartosc) {
                $old[$etykieta] = $przed[$etykieta] ?? '(z szablonu)';
                $attributes[$etykieta] = $wartosc;
            }
        }

        $activity->properties = $activity->properties
            ->merge(['old' => $old, 'attributes' => $attributes, 'subject_title' => $this->nazwa()]);
    }

    /** [etykieta pola => opis wartości]; elementy list jako „Liczby 2 - Opis” */
    private function splaszcz(array $pola, array $dane, string $przedrostek = ''): array
    {
        $wynik = [];

        foreach ($pola as $pole => $definicja) {
            $etykieta = $przedrostek . ($definicja['etykieta'] ?? $pole);

            if ($definicja['typ'] === 'lista') {
                foreach (array_keys($definicja['domyslnie']) as $i) {
                    $wynik += $this->splaszcz($definicja['pola'], $dane[$pole][$i] ?? [], $etykieta . ' ' . ($i + 1) . ' - ');
                }
                continue;
            }

            $wynik[$etykieta] = $this->opisWartosci($definicja, $dane[$pole] ?? null);
        }

        return $wynik;
    }

    private function opisWartosci(array $definicja, $wartosc): string
    {
        if ($wartosc === null || $wartosc === '' || $wartosc === []) {
            return '(z szablonu)';
        }

        return match ($definicja['typ']) {
            'html' => Str::limit(trim(html_entity_decode(strip_tags(preg_replace('/<br\s*\/?>/i', ' / ', $wartosc)))), 120),
            'link' => trim(($wartosc['tekst'] ?? '') . ' (' . ($wartosc['adres'] ?? '') . ')'),
            'obrazek' => trim(($wartosc['plik'] ?? 'zdjęcie z szablonu') . (($wartosc['alt'] ?? '') !== '' ? ', ALT: ' . $wartosc['alt'] : '')),
            'ikona' => $definicja['opcje'][$wartosc][0] ?? (string) $wartosc,
            default => (string) $wartosc,
        };
    }
}
