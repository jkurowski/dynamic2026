<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Services\Activity\ActivityChangeDescriber;

class Client extends Authenticatable
{
    use LogsActivity;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';
    protected $attributes = [
        'source' => 1,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lastname',
        'mail',
        'mail2',
        'phone',
        'phone2',
        'source',
        'source_additional',
        'status',
        'deal_additional',
        'room',
        'area',
        'purpose',
        'budget'
    ];

    public function properties()
    {
        return $this->hasMany(Property::class, 'client_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Klienci')
            ->logFillable()
            ->logExcept(['password', 'remember_token'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => ActivityChangeDescriber::describe($this, $eventName));
    }
}
