<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Page extends Model
{
    use NodeTrait, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active',
        'parent_id',
        'title',
        'title_text',
        'content',
        'content_header',
        'file_header',
        'meta_title',
        'meta_description',
        'meta_robots'
    ];

    public static function mainmenu()
    {
        return view('layouts.partials.menu', [
            'pages' => self::withDepth()->defaultOrder()->get()->toTree()
        ]);
    }

    public static function sidemenu(int $id)
    {
        return view('layouts.partials.sidemenu', [
            'pages' => self::descendantsOf($id)->toTree()
        ]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Strony')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Utworzono stronę',
                'updated' => 'Zaktualizowano stronę',
                'deleted' => 'Usunięto stronę',
                default => $eventName,
            });
    }

    /** Nazwa obiektu zapisana przy wpisie - zostaje w dzienniku także po usunięciu obiektu */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->merge(['subject_title' => $this->title]);
    }
}
