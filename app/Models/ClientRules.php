<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientRules extends Model
{
    const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_rules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'rule_id',
        'duration',
        'months',
        'ip',
        'source',
        'status',
        'text'
    ];

}
