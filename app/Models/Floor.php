<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Floor extends Model
{
    use HasTranslations;
    public array $translatable = ['name', 'meta_title', 'meta_description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id',
        'building_id',
        'name',
        'number',
        'position',
        'type',
        'area_range',
        'price_range',
        'room_range',
        'html',
        'cords',
        'file',
        'file_webp',
        'meta_title',
        'meta_description',
        'active'
    ];

    /**
     * Get floor properties
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany('App\Models\Property');
    }

    public function pricesProperties(): HasMany
    {
        return $this->hasMany('App\Models\Property')
            ->whereIn('status', [1, 2])
            ->where('type', 1);
    }

    public function findNext(int $investment, int $current_position, int $building = null)
    {
        $next = $this->where('investment_id', $investment);

        if ($building) {
            $next->where('building_id', $building);
        }

        $next->where('position', '>', $current_position);

        // Return the first matching record
        return $next->first();
    }

    public function findPrev(int $investment, int $current_position, int $building = null)
    {
        $prev = $this->where('investment_id', $investment);

        if ($building) {
            $prev->where('building_id', $building);
        }

        $prev->orderByDesc('position')->where('position', '<', $current_position);

        // Return the first matching record
        return $prev->first();
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function ($floor) {
            $floor->properties()->each(function($property) {
                $property->delete();
            });
        });
    }
}
