<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Offer extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'template_id',
        'email_bcc',
        'date_end',
        'title',
        'message',
        'offer',
        'attachments'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}