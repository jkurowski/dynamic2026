<?php namespace App\Repositories;

use App\Models\Slider;

class SliderRepository extends BaseRepository
{
    protected $model;

    public function __construct(Slider $model)
    {
        parent::__construct($model);
    }
}
