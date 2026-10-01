<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InvestmentCompany extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'legal_form',
        'krs_number',
        'ceidg_number',
        'nip',
        'regon',
        'phone',
        'email',
        'fax',
        'website',
        'province',
        'district',
        'municipality',
        'city',
        'street',
        'building_number',
        'apartment_number',
        'postal_code',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Firmy inwestycji')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
