<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyPayment extends Model
{
    protected $fillable = [
        'property_id',
        'percent',
        'amount',
        'due_date',
        'status',
        'paid_at'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
