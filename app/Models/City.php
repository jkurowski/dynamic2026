<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class City extends Model
{
    use HasTranslations, LogsActivity;
    public array $translatable = ['name', 'footer', 'contact_title', 'contact_text'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /** Obrazki kodów QR biur: {KATALOG}plik.png + {KATALOG}webp/plik.webp */
    public const KATALOG_QR = 'uploads/biura/';

    protected $fillable = [
        'name',
        'slug',
        'address',
        'map_link',
        'file',
        'footer',
        'contact_title',
        'contact_text',
        'email',
        'phone',
        'phone2',
        'address_line_1',
        'address_line_2',
        'short_message',
        'working_hours',
        'lat',
        'lng',
        'active',
        'completed',
        'sort'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Miasta')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /*
     * Biura sprzedaży na froncie (strona Kontakt, zakładki biur w sekcji kontaktu, mapa biur).
     * Pola z mini edytora (address, phone, working_hours) to HTML z liniami rozdzielonymi <br>.
     */

    public function scopeAktywne(Builder $query): Builder
    {
        return $query->where('active', 1)->orderBy('sort')->orderBy('id');
    }

    /** Aktywne biura do wyświetlenia (raz na żądanie - sekcja kontaktu i strona Kontakt pytają o to samo) */
    public static function biura()
    {
        return app()->bound('biura.aktywne')
            ? app('biura.aktywne')
            : app()->instance('biura.aktywne', static::aktywne()->get());
    }

    /** Telefon z numerami zamienionymi na odnośniki tel: (zakładki biur). Treść z gotowymi linkami zostaje bez zmian. */
    public function telefonZLinkami(): string
    {
        $html = (string) $this->phone;

        if (stripos($html, '<a ') !== false) {
            return $html;
        }

        return preg_replace_callback('/\+?\d[\d \-]{7,}\d/', function ($m) {
            return '<a href="tel:' . preg_replace('/[^\d+]/', '', $m[0]) . '">' . $m[0] . '</a>';
        }, $html);
    }

    /** Kod QR biura (PNG albo WebP); bez obrazka w panelu - kod z szablonu */
    public function qr(bool $webp = false): string
    {
        if (!$this->file) {
            return asset('img/kod-qr.' . ($webp ? 'webp' : 'png'));
        }

        $base = pathinfo($this->file, PATHINFO_FILENAME);

        return asset(self::KATALOG_QR . ($webp ? 'webp/' . $base . '.webp' : $this->file));
    }

    /**
     * Położenie pinezki na mapie biur w % (left, top) z lat/lng - kalibracja w config/mapa.php.
     * null, gdy biuro nie ma współrzędnych albo wypada poza obrazek mapy.
     */
    public function pozycjaNaMapie(): ?array
    {
        if (!is_numeric($this->lat) || !is_numeric($this->lng)) {
            return null;
        }

        [[$lat1, $lng1, $x1, $y1], [$lat2, $lng2, $x2, $y2]] = config('mapa.kalibracja');

        $left = $x1 + ($this->lng - $lng1) * ($x2 - $x1) / ($lng2 - $lng1);
        $top = $y1 + ($this->lat - $lat1) * ($y2 - $y1) / ($lat2 - $lat1);

        if ($left < 0 || $left > 100 || $top < 0 || $top > 100) {
            return null;
        }

        return ['left' => round($left, 1), 'top' => round($top, 1)];
    }
}
