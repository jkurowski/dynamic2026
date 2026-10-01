<?php namespace App\Repositories;

use App\Models\ClientMessage;

class InboxRepository extends BaseRepository
{
    protected $model;

    public function __construct(ClientMessage $model)
    {
        parent::__construct($model);
    }
}
