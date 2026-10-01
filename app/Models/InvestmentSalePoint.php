<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentSalePoint extends Model
{
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
}
