<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyPrice extends Model
{
    protected $table = 'property_prices';
    public $timestamps = false;

    protected $fillable = [
        'property_id',
        'price_brutto',
        'new_price_brutto',
        'area',
        'promotion',
        'changed_at',
        'changed_by',
    ];

    protected $casts = [
        'price_brutto' => 'decimal:2',
        'area' => 'decimal:2',
        'changed_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getPricePerSquareMeterAttribute(): ?float
    {
        return $this->area > 0 ? round($this->price_brutto / $this->area, 2) : null;
    }
}
