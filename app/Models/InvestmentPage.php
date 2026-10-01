<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPage extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id',
        'title',
        'content',
        'meta_title',
        'meta_description',
        'meta_robots',
        'sort',
        'active',
        'contact_form',
        'cta_text',
        'cta_button',
        'cta_link'
    ];
}
