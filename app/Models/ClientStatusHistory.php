<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientStatusHistory extends Model
{
    protected $fillable = [
        'client_id', 'old_status', 'new_status', 'user_id', 'changed_at',
    ];

    protected $dates = [
        'changed_at',
    ];

    // Relacja do klienta (Client)
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relacja do użytkownika (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
