<?php namespace App\Repositories;

use App\Models\Greylist;

class GreylistRepository extends BaseRepository
{
    protected $model;

    public function __construct(Greylist $model)
    {
        parent::__construct($model);
    }
}
