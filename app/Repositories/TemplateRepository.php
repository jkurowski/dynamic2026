<?php namespace App\Repositories;

use App\Models\Template;

class TemplateRepository extends BaseRepository
{
    protected $model;

    public function __construct(Template $model)
    {
        parent::__construct($model);
    }
}
