<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateSection extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'email_template_id',
        'uuid',
        'type',
        'content',
        'position',
        'file'
    ];
}
