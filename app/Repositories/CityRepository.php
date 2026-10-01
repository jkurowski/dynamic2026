<?php namespace App\Repositories;

use App\Models\City;

class CityRepository extends BaseRepository
{
    protected $model;

    public function __construct(City $model)
    {
        parent::__construct($model);
    }
}
