<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;


class Gallery extends Model
{
    use LogsActivity;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'gallery_id',
        'slug',
        'text',
        'file',
        'file_webp',
        'sort',
        'status'
    ];

    /**
     * Get images
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany('App\Models\Image')->orderBy('sort');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Galerie')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Utworzono galerię',
                'updated' => 'Zaktualizowano galerię',
                'deleted' => 'Usunięto galerię',
                default => $eventName,
            });
    }

    /** Nazwa obiektu zapisana przy wpisie - zostaje w dzienniku także po usunięciu obiektu */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->merge(['subject_title' => $this->name]);
    }
}
