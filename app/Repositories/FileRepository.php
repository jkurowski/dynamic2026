<?php namespace App\Repositories;

use App\Models\File;

class FileRepository extends BaseRepository
{
    protected $model;

    public function __construct(File $model)
    {
        parent::__construct($model);
    }
}
