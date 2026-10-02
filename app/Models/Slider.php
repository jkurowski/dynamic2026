<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Slider extends Model
{
    use LogsActivity;

    /** Zdjęcia slajdów: {KATALOG}{szerokosc}/plik.jpg, {KATALOG}{szerokosc}/webp/plik.webp, {KATALOG}thumbs/plik.jpg */
    public const KATALOG = 'uploads/slider/';

    /**
     * Slajdy z makiety (dynamic-front) - pokazywane, dopóki w panelu nie ma żadnego aktywnego slajdu.
     */
    private const SLAJDY_MAKIETY = [
        ['WARSZAWA · MOKOTÓW', 'Dom Hygge Twin', 'hero', true],
        ['CHYLICE · KONSTANCIN-JEZIORNA', 'Konstancin Riverside House', 'hero-konstancin-hd', false],
        ['ZALESIE GÓRNE · GM. PIASECZNO', 'Segmenty Lake Village', 'hero-lake-hd', false],
        ['NOWA WOLA · GM. LESZNOWOLA', 'Zespół willowy Zielona Polana', 'poznaj-onas', false],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'text',
        'file',
        'file_alt',
        'link',
        'link_button',
        'link_target',
        'opacity',
        'color',
        'active',
        'sort'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Slider')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Dodano slajd',
                'updated' => 'Zaktualizowano slajd',
                'deleted' => 'Usunięto slajd',
                default => $eventName,
            });
    }

    /** Nazwa obiektu zapisana przy wpisie - zostaje w dzienniku także po usunięciu obiektu */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->merge(['subject_title' => $this->title]);
    }

    /**
     * Slajdy hero na stronę główną (pierwszy renderuje Blade, całą listę dostaje js/slider.js).
     * Aktywne slajdy z panelu (active = 1) wg kolejności; gdy żadnego nie ma - slajdy z makiety.
     */
    public static function slajdyHero(): array
    {
        $slajdy = static::where('active', 1)->whereNotNull('file')->orderBy('sort')->orderBy('id')->get()
            ->map(fn (Slider $s) => $s->doHero())
            ->all();

        return $slajdy ?: static::slajdyMakiety();
    }

    /** Dane jednego slajdu dla hero */
    public function doHero(): array
    {
        $srcset = fn (string $format) => collect(config('images.slider.rozmiary'))
            ->map(fn ($h, $w) => $this->zdjecie($w, $format === 'webp') . ' ' . $w . 'w')
            ->implode(', ');

        return [
            'lokalizacja' => (string) $this->text,
            'tytul' => $this->title,
            'link' => $this->link ?: route('menu.show', ['uri' => 'inwestycje']),
            'przycisk' => $this->link_button ?: 'Zobacz inwestycję',
            'cel' => $this->link_target ?: null,
            'alt' => $this->file_alt ?: 'Wizualizacja inwestycji ' . $this->title,
            'zdjecieJpg' => $this->zdjecie(1920),
            'srcsetJpg' => $srcset('jpg'),
            'srcsetWebp' => $srcset('webp'),
            'szerokosc' => 1920,
            'wysokosc' => config('images.slider.rozmiary')[1920],
        ];
    }

    public function zdjecie(int $szerokosc = 1920, bool $webp = false): string
    {
        $base = pathinfo($this->file, PATHINFO_FILENAME);

        return asset(self::KATALOG . $szerokosc . '/' . ($webp ? 'webp/' . $base . '.webp' : $base . '.jpg'));
    }

    private static function slajdyMakiety(): array
    {
        return array_map(function ($s) {
            [$lokalizacja, $tytul, $plik, $responsywne] = $s;
            $srcset = fn ($ext) => $responsywne
                ? asset("img/hero-768.$ext") . ' 768w, ' . asset("img/hero-1280.$ext") . ' 1280w, ' . asset("img/hero-1920.$ext") . ' 1920w'
                : asset("img/$plik.$ext");

            return [
                'lokalizacja' => $lokalizacja,
                'tytul' => $tytul,
                'link' => route('menu.show', ['uri' => 'inwestycje']),
                'przycisk' => 'Zobacz inwestycję',
                'cel' => null,
                'alt' => 'Wizualizacja inwestycji ' . $tytul,
                'zdjecieJpg' => asset('img/' . ($responsywne ? 'hero-1920' : $plik) . '.jpg'),
                'srcsetJpg' => $srcset('jpg'),
                'srcsetWebp' => $srcset('webp'),
                'szerokosc' => 1925,
                'wysokosc' => 1155,
            ];
        }, self::SLAJDY_MAKIETY);
    }

    /** Usuwa wszystkie pliki zdjęcia slajdu (każdy rozmiar JPG + WebP, miniaturę i pliki starego formatu) */
    public function usunPliki(): void
    {
        if (!$this->file) {
            return;
        }

        $base = pathinfo($this->file, PATHINFO_FILENAME);
        $pliki = [self::KATALOG . $this->file, self::KATALOG . 'thumbs/' . $this->file,
            self::KATALOG . 'webp/' . $base . '.webp', self::KATALOG . 'mobile/' . $base . '.webp'];

        foreach (array_keys(config('images.slider.rozmiary')) as $w) {
            $pliki[] = self::KATALOG . $w . '/' . $base . '.jpg';
            $pliki[] = self::KATALOG . $w . '/webp/' . $base . '.webp';
        }

        foreach ($pliki as $plik) {
            if (File::isFile(public_path($plik))) {
                File::delete(public_path($plik));
            }
        }
    }
}
