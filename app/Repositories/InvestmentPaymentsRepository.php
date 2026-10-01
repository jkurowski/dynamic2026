<?php namespace App\Repositories;

use App\Models\InvestmentPayment;

class InvestmentPaymentsRepository extends BaseRepository
{
    protected $model;

    public function __construct(InvestmentPayment $model)
    {
        parent::__construct($model);
    }
}
