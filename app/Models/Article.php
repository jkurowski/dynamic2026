<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Article extends Model
{
    use HasTranslations, LogsActivity;
    public array $translatable = ['title', 'content_entry', 'content', 'meta_title', 'meta_description'];

    /**
     * Kategorie wpisu - etykieta na karcie (lista aktualności, karuzela na stronie głównej), jak w projekcie.
     */
    public const KATEGORIE = ['NOWA INWESTYCJA', 'DZIENNIK INWESTYCJI', 'PORADNIK'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'old_id',
        'title',
        'slug',
        'category',
        'content_entry',
        'content',
        'file',
        'file_webp',
        'file_facebook',
        'file_alt',
        'meta_title',
        'meta_description',
        'meta_robots',
        'status',
        'sort',
        'posted_at'
    ];

    /** Opublikowane wpisy, od najnowszego (data wyświetlenia, inaczej data dodania) */
    public function scopeOpublikowane($query)
    {
        return $query->where('status', 1)
            ->orderByRaw("COALESCE(NULLIF(posted_at, ''), DATE(created_at)) DESC")
            ->orderByDesc('id');
    }

    public function link(): string
    {
        return route('aktualnosci.show', $this->slug);
    }

    /** Data publikacji (Y-m-d): pole "Data wyświetlenia", inaczej data dodania */
    public function dataPublikacji(): ?string
    {
        return $this->posted_at ?: $this->created_at?->format('Y-m-d');
    }

    /**
     * Adres zdjęcia wpisu. $rozmiar: 'thumb' (karty) albo 'big' (strona wpisu), $webp - wersja WebP.
     * Bez zdjęcia - obrazek zastępczy z szablonu.
     */
    public function zdjecie(string $rozmiar = 'thumb', bool $webp = false): string
    {
        $plik = $webp ? $this->file_webp : $this->file;

        if (!$plik) {
            return asset('img/aktualnosc.' . ($webp ? 'webp' : 'jpg'));
        }

        $katalog = 'uploads/articles/' . ($rozmiar === 'thumb' ? 'thumbs/' : '') . ($webp ? 'webp/' : '');

        return asset($katalog . $plik);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Aktualności')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Dodano wpis',
                'updated' => 'Zaktualizowano wpis',
                'deleted' => 'Usunięto wpis',
                default => $eventName,
            });
    }

    /** Nazwa obiektu zapisana przy wpisie - zostaje w dzienniku także po usunięciu obiektu */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->merge(['subject_title' => $this->title]);
    }
}
