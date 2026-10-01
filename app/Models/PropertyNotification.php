<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PropertyNotification extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['property_id', 'email', 'hash'];
}
