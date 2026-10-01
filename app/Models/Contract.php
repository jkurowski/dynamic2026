<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['name', 'description', 'numbering', 'template'];

    public function contractTemplates()
    {
        return $this->hasMany(ContractTemplate::class, 'contract_id');
    }
}
