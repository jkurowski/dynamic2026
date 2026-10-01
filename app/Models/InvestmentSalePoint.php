<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InvestmentSalePoint extends Model
{
    use LogsActivity;

    protected $fillable = [
        'province',
        'district',
        'municipality',
        'city',
        'street',
        'building_number',
        'apartment_number',
        'postal_code',
        'additional_locations',
        'contact_method',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Biura sprzedaży')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
