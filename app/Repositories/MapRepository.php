<?php namespace App\Repositories;

use App\Models\Map;

class MapRepository extends BaseRepository
{
    protected $model;

    public function __construct(Map $model)
    {
        parent::__construct($model);
    }
}
