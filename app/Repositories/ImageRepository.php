<?php namespace App\Repositories;

use App\Models\Image;

class ImageRepository extends BaseRepository
{
    protected $model;

    public function __construct(Image $model)
    {
        parent::__construct($model);
    }
}
