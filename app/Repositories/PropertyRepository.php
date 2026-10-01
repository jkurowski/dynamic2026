<?php namespace App\Repositories;

use App\Models\Property;

class PropertyRepository extends BaseRepository
{
    protected $model;

    public function __construct(Property $model)
    {
        parent::__construct($model);
    }
}
