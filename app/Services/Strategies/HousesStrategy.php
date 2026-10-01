<?php

namespace App\Services\Strategies;

use App\Interfaces\InvestmentTypeStrategy;
use App\Models\Investment;
use Illuminate\Http\Request;

class HousesStrategy implements InvestmentTypeStrategy
{
    private Investment $investment;
    private Request $request;

    private $investment_room;

    public function __construct(Request $request, Investment $investment)
    {
        $this->investment = $investment;
        $this->request = $request;
    }
    public function handle()
    {
        $this->investment_room = $this->investment->load(array(
            'properties' => function ($query) {
                if ($this->request->input('rooms')) {
                    $query->where('rooms', $this->request->input('rooms'));
                }
                if ($this->request->input('status')) {
                    $query->where('status', $this->request->input('status'));
                }
                if ($this->request->input('sort')) {
                    $order_param = explode(':', $this->request->input('sort'));
                    $column = $order_param[0];
                    $direction = $order_param[1];
                    $query->orderBy($column, $direction);
                }
            }
        ));

        return $this->investment_room->properties;
    }

    public function getInvestmentRoom()
    {
        return $this->investment_room;
    }
}
