<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentSection extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id',
        'fields',
        'title',
        'subtitle',
        'content',
        'code',
        'file',
        'file_alt',
        'lock',
        'sort'
    ];

    public function getFieldsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function setFieldsAttribute($value)
    {
        $this->attributes['fields'] = json_encode($value);
    }
}
