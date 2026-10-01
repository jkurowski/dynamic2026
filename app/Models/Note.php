<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{

    protected $fillable = ['user_id', 'model_type', 'model_id', 'text', 'pinned'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relationship
    public function relatedModel()
    {
        return $this->morphTo('model');
    }
}