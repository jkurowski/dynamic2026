<?php namespace App\Repositories;

use App\Models\CustomField;

class CustomFieldRepository extends BaseRepository
{
    protected $model;

    public function __construct(CustomField $model)
    {
        parent::__construct($model);
    }
}
