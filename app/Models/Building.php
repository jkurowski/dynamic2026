<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Building extends Model
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
        'name',
        'number',
        'html',
        'cords',
        'file',
        'file_webp',
        'meta_title',
        'meta_description',
        'meta_robots',
        'active',
        'content',
        'area_range',
        'room_range',
        'price_range'
    ];

    /**
     * Get building floors
     * @return HasMany
     */
    public function floors(): HasMany
    {
        return $this->hasMany('App\Models\Floor');
    }

    /**
     * Get building floors with properties count
     * @return Collection
     */
    public function floorsWithCount(): Collection
    {
        return $this->hasMany('App\Models\Floor')->withCount('properties')->get(['id', 'name']);
    }

    /**
     * Get building properties
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

    public function findNext(int $investment, int $id)
    {
        $next = $this->where('investment_id', $investment);
        $next->where('id', '>', $id)->where('active', 1);
        return $next->first();
    }

    public function findPrev(int $investment, int $id)
    {
        $prev = $this->where('investment_id', $investment);
        $prev->orderByDesc('id')->where('id', '<', $id)->where('active', 1);
        return $prev->first();
    }

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function ($building) {
            $building->floors()->each(function($floor) {
                $floor->delete();
            });
        });
    }
}
