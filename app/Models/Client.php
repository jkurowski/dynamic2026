<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';
    protected $attributes = [
        'source' => 1,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lastname',
        'mail',
        'mail2',
        'phone',
        'phone2',
        'source',
        'source_additional',
        'status',
        'deal_additional',
        'room',
        'area',
        'purpose',
        'budget'
    ];

    public function offers()
    {
        return $this->hasMany(Offer::class, 'client_id', 'id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'client_id', 'id');
    }
}
