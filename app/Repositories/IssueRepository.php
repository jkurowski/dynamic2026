<?php namespace App\Repositories;

use App\Models\Issue;

class IssueRepository extends BaseRepository
{
    protected $model;

    public function __construct(Issue $model)
    {
        parent::__construct($model);
    }
}
