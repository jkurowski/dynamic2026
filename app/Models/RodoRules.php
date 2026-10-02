<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RodoRules extends Model
{
    use LogsActivity;

    /**
     * Kody formularzy, w których klauzula może się wyświetlać (kolumna `forms`, JSON jako TEXT).
     * Pusta lista / NULL = klauzula we wszystkich formularzach.
     */
    public const FORM_CONTACT = 'kontakt';

    public const FORMS = [
        self::FORM_CONTACT => 'Formularz kontaktowy (strona, inwestycja, lokal)',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title',
        'text',
        'required',
        'time',
        'active',
        'sort',
        'forms'
    ];

    protected $casts = [
        'forms' => 'array',
    ];

    /**
     * Inwestycje, do których zawężono klauzulę. Brak przypisań = klauzula we wszystkich inwestycjach.
     */
    public function investments()
    {
        return $this->belongsToMany(Investment::class, 'rodo_rule_investment', 'rodo_rule_id', 'investment_id');
    }

    /**
     * Klauzule pokazywane w danym formularzu: aktywne, przypisane do formularza (lub do wszystkich)
     * i do inwestycji (lub niezawężone). Bez inwestycji - tylko klauzule niezawężone.
     */
    public function scopeForForm(Builder $query, string $form, ?int $investmentId = null): Builder
    {
        return $query
            ->where('active', 1)
            ->where(function (Builder $q) use ($form) {
                $q->whereNull('forms')
                    ->orWhere('forms', '[]')
                    // LIKE zamiast whereJsonContains (JSON_CONTAINS) - kolumna to TEXT, baza docelowa nie ma typu JSON.
                    // Kody formularzy to proste słowa, więc "kod" w cudzysłowie nie trafi w inny kod.
                    ->orWhere('forms', 'like', '%"' . $form . '"%');
            })
            ->where(function (Builder $q) use ($investmentId) {
                $q->whereDoesntHave('investments');

                if ($investmentId !== null) {
                    $q->orWhereHas('investments', fn (Builder $i) => $i->whereKey($investmentId));
                }
            })
            ->orderBy('sort')
            ->orderBy('id');
    }

    /** Do widoku formularza */
    public static function forFormAndInvestment(string $form, ?int $investmentId = null)
    {
        return static::query()->forForm($form, $investmentId)->get();
    }

    /** Do walidacji - ten sam zestaw co w widoku, tylko wymagane */
    public static function requiredForFormAndInvestment(string $form, ?int $investmentId = null)
    {
        return static::query()->forForm($form, $investmentId)->where('required', 1)->get();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Klauzule RODO')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
