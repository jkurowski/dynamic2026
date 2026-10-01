<?php namespace App\Repositories;

use App\Models\Building;

class BuildingRepository extends BaseRepository
{
    protected $model;

    public function __construct(Building $model)
    {
        parent::__construct($model);
    }
}
