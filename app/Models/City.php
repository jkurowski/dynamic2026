<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasTranslations;
    public array $translatable = ['name', 'footer', 'contact_title', 'contact_text'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'footer',
        'contact_title',
        'contact_text',
        'email',
        'phone',
        'phone2',
        'address_line_1',
        'address_line_2',
        'short_message',
        'working_hours',
        'lat',
        'lng',
        'active',
        'completed',
        'sort'
    ];
}