<?php

namespace App\Models;

use Illuminate\Support\Facades\File as StorageFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Kalnoy\Nestedset\NodeTrait;

class File extends Model
{
    use NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'user_id',
        'type',
        'name',
        'description',
        'file',
        'extension',
        'mime',
        'size',
        'download'
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->user_id = Auth::id();
        });
    }
}









