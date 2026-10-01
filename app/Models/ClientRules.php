<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientRules extends Model
{
    use LogsActivity;

    const UPDATED_AT = null;

    public const STATUS_GRANTED = 1;
    public const STATUS_WITHDRAWN = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_rules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'rule_id',
        'duration',
        'months',
        'ip',
        'source',
        'status',
        'text',
        'canceled_at'
    ];

    /**
     * Zgody RODO: logujemy tylko stan zgody (nadanie, wycofanie, okres). Treść klauzuli i IP są w samym rekordzie.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Zgody RODO')
            ->logOnly(['status', 'duration', 'months', 'canceled_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
