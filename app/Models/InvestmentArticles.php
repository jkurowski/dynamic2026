<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class InvestmentArticles extends Model
{
    use HasTranslations;
    public array $translatable = ['title', 'content_entry', 'content', 'meta_title', 'meta_description'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id',
        'title',
        'date',
        'content',
        'content_entry',
        'meta_title',
        'meta_description',
        'meta_robots',
        'active',
        'file',
        'file_webp'
    ];
}
