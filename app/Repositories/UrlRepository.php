<?php namespace App\Repositories;

use App\Models\Url;

class UrlRepository extends BaseRepository
{
    protected $model;

    public function __construct(Url $model)
    {
        parent::__construct($model);
    }
}
