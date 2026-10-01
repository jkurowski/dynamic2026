<?php namespace App\Repositories;

use App\Models\InvestmentArticles;

class InvestmentArticleRepository extends BaseRepository
{
    protected $model;

    public function __construct(InvestmentArticles $model)
    {
        parent::__construct($model);
    }
}
