<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['title', 'amount'];

    public function schedules()
    {
        return $this->hasMany(PaymentSchedule::class);
    }
}
