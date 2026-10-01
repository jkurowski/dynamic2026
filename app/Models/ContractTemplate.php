<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractTemplate extends Model
{
    public $timestamps = false;
    protected $fillable = ['contract_id', 'placeholders'];
}
