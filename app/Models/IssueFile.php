<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueFile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'issue_id',
        'name',
        'description',
        'file',
        'extension',
        'mime',
        'size',
        'type'
    ];

    /**
     * Get uploaded file user
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select('name', 'surname');
    }
}
