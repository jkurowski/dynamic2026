<?php namespace App\Repositories;

use App\Models\Floor;

class BuildingFloorRepository extends BaseRepository
{
    protected $model;

    public function __construct(Floor $model)
    {
        parent::__construct($model);
    }
}
