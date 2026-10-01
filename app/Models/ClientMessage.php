<?php

namespace App\Models;

use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Model;

class ClientMessage extends Model
{

    //use BroadcastsEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_msg';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'user_id',
        'client_id',
        'message',
        'source',
        'ip',
        'mark_at',
        'arguments'
    ];

    public function answers()
    {
        return $this->hasMany(self::class, 'parent_id')->latest();
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
