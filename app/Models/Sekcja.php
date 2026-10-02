<?php

namespace App\Models;

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

    public function pole(string $pole): array
    {
        $definicja = $this->schemat()['pola'][$pole] ?? null;
        if (!$definicja) {
            throw new \InvalidArgumentException("Sekcja „{$this->klucz}” nie ma pola „{$pole}”");
        }

        return $definicja;
    }

    /** Zapisana wartość albo domyślna z configu (null/'' w zapisie = brak wartości) */
    public function wartosc(string $pole)
    {
        $zapisane = $this->dane[$pole] ?? null;

        return ($zapisane === null || $zapisane === '') ? ($this->pole($pole)['domyslnie'] ?? null) : $zapisane;
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
        $domyslnie = $this->pole($pole)['domyslnie'] ?? [];
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
        $definicja = $this->pole($pole);
        $domyslnie = $definicja['domyslnie'] ?? [];
        $zapisane = $this->dane[$pole] ?? [];
        [$szerokosc, $wysokosc] = $definicja['rozmiar'] ?? $definicja['kadr'];

        $plik = $zapisane['plik'] ?? null;
        $wlasny = $plik && File::isFile(public_path(self::KATALOG . $plik));

        return (object) [
            'jpg' => asset($wlasny ? self::KATALOG . $plik : $domyslnie['jpg']),
            'webp' => asset($wlasny ? self::KATALOG . 'webp/' . pathinfo($plik, PATHINFO_FILENAME) . '.webp' : $domyslnie['webp']),
            'alt' => ($zapisane['alt'] ?? '') !== '' ? $zapisane['alt'] : ($domyslnie['alt'] ?? ''),
            'szerokosc' => $szerokosc,
            'wysokosc' => $wysokosc,
            'wlasny' => $wlasny,
        ];
    }

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
        $old = $attributes = [];

        foreach ($this->schemat()['pola'] as $pole => $definicja) {
            $przed = $this->opisWartosci($definicja, $stare[$pole] ?? null);
            $po = $this->opisWartosci($definicja, $nowe[$pole] ?? null);
            if ($przed !== $po) {
                $etykieta = $definicja['etykieta'] ?? $pole;
                $old[$etykieta] = $przed;
                $attributes[$etykieta] = $po;
            }
        }

        $activity->properties = $activity->properties
            ->merge(['old' => $old, 'attributes' => $attributes, 'subject_title' => $this->nazwa()]);
    }

    private function opisWartosci(array $definicja, $wartosc): string
    {
        if ($wartosc === null || $wartosc === '' || $wartosc === []) {
            return '(z szablonu)';
        }

        return match ($definicja['typ']) {
            'html' => Str::limit(trim(html_entity_decode(strip_tags($wartosc))), 120),
            'link' => trim(($wartosc['tekst'] ?? '') . ' (' . ($wartosc['adres'] ?? '') . ')'),
            'obrazek' => trim(($wartosc['plik'] ?? 'zdjęcie z szablonu') . (($wartosc['alt'] ?? '') !== '' ? ', ALT: ' . $wartosc['alt'] : '')),
            default => (string) $wartosc,
        };
    }
}
