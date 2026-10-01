<?php namespace App\Repositories\Facebook;

use App\Models\FacebookPage;
use App\Repositories\BaseRepository;

class FacebookPageRepository extends BaseRepository
{
    protected $model;

    public function __construct(FacebookPage $model)
    {
        parent::__construct($model);
    }
}
