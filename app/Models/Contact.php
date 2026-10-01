<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone_1',
        'phone_2',
        'category_id',
        'note'
    ];
}
