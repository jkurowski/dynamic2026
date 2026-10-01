<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentCompany extends Model
{
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
}
