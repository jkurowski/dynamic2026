<?php namespace App\Repositories;

use App\Models\InvestmentPage;
use Illuminate\Support\Collection;

class InvestmentPageRepository extends BaseRepository
{
    protected $model;

    public function __construct(InvestmentPage $model)
    {
        parent::__construct($model);
    }

    public function allPageSort($order, $investment_id): Collection
    {
        return $this->model
            ->where('investment_id', $investment_id)
            ->orderBy('sort', $order)
            ->get();
    }
}
