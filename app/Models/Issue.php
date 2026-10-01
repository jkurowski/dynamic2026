<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'user_id',
        'contact_id',
        'investment_id',
        'property_id',
        'department_id',
        'title',
        'note',
        'status',
        'type',
        'start',
        'history'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'contact_id', 'id');
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function issueFiles()
    {
        return $this->hasMany(IssueFile::class, 'issue_id');
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'model')->orderBy('id', 'desc');
    }
}
