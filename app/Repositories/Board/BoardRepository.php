<?php namespace App\Repositories\Board;

use App\Models\Board;
use App\Repositories\BaseRepository;

class BoardRepository extends BaseRepository
{
    protected $model;

    public function __construct(Board $model)
    {
        parent::__construct($model);
    }
}
