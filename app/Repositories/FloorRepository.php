<?php namespace App\Repositories;

use App\Models\Floor;

class FloorRepository extends BaseRepository
{
    protected $model;

    public function __construct(Floor $model)
    {
        parent::__construct($model);
    }

    public function getUniqueRooms(object $query)
    {
        return $query->orderBy('rooms', 'ASC')->get()->unique('rooms')->pluck('rooms');
    }
}
