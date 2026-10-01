<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Slider extends Model
{
    use LogsActivity;

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
}
