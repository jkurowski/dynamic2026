<?php namespace App\Repositories;

use App\Models\Section;

class SectionRepository extends BaseRepository
{
    protected $model;

    public function __construct(Section $model)
    {
        parent::__construct($model);
    }
}
