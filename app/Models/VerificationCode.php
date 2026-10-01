<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VerificationCode extends Model
{
    public $timestamps = false;

    protected $fillable = ['email', 'code', 'expires_at', 'created_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ]; // Ensure expires_at is treated as a datetime

    public function isExpired()
    {
        return $this->expires_at->lt(Carbon::now());
    }
}