<?php namespace App\Repositories;

use App\Models\Job;

class JobRepository extends BaseRepository
{
    protected $model;

    public function __construct(Job $model)
    {
        parent::__construct($model);
    }
}
