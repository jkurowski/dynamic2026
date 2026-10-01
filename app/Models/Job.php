<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Job extends Model
{
    protected $table = 'jobofferts';

    use HasTranslations;
    public array $translatable = ['name', 'text'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'city',
        'email',
        'text',
        'active'
    ];
}
