<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'private',
        'description'
    ];

    /**
     * Get the stages for the board.
     */
    public function stages()
    {
        return $this->hasMany(Stage::class)->orderBy('sort')->whereUserId(auth()->id());
    }

    public function allStages()
    {
        return $this->hasMany(Stage::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select('name', 'surname');
    }
}
