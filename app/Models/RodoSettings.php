<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RodoSettings extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    const UPDATED_AT = null;
    const CREATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'obligation',
        'status'
    ];
}
