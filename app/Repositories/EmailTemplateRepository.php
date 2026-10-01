<?php namespace App\Repositories;

use App\Models\EmailTemplate;

class EmailTemplateRepository extends BaseRepository
{
    protected $model;

    public function __construct(EmailTemplate $model)
    {
        parent::__construct($model);
    }

    public function findByMeta(string $key, string $value)
    {
        return $this->model->where('meta->' . $key, $value)->first();
    }
}