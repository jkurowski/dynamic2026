<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPayment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id',
        'due_date',
        'amount'
    ];


}
