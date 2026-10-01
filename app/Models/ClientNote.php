<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientNote extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'text',
        'pinned'
    ];

    /**
     * Get note user
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select('name', 'surname', 'email');
    }
}
