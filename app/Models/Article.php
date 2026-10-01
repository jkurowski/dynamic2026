<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Article extends Model
{
    use HasTranslations;
    public array $translatable = ['title', 'content_entry', 'content', 'meta_title', 'meta_description'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'old_id',
        'title',
        'slug',
        'content_entry',
        'content',
        'file',
        'file_webp',
        'file_facebook',
        'file_alt',
        'meta_title',
        'meta_description',
        'meta_robots',
        'status',
        'sort',
        'posted_at'
    ];
}
