<?php namespace App\Repositories;

use App\Models\InvestmentSection;

class InvestmentSectionRepository extends BaseRepository
{
    protected $model;

    public function __construct(InvestmentSection $model)
    {
        parent::__construct($model);
    }
}
