<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSchedule extends Model
{
    protected $fillable = ['payment_id', 'due_date', 'amount'];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
