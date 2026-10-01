<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyPriceComponent extends Model
{
    protected $fillable = [
        'name',
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_price_component_property')
            ->withPivot('value', 'value_m2', 'category')
            ->withTimestamps();
    }
}
