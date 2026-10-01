<?php

namespace App\Services\Strategies;

use App\Interfaces\InvestmentTypeStrategy;
use App\Models\Investment;
use Illuminate\Http\Request;

class SingleBuildingStrategy implements InvestmentTypeStrategy
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
            'floorRooms' => function ($query) {
                $query->orderBy('highlighted', 'DESC');
                $query->orderBy('number_order', 'ASC');

                if ($this->request->input('rooms')) {
                    $query->where('rooms', $this->request->input('rooms'));
                }
                if ($this->request->input('status')) {
                    $query->where('status', $this->request->input('status'));
                }

                if ($this->investment->show_properties == 3) {
                    $query->where('status', 1);
                }

                if ($this->request->input('area')) {
                    $area_param = explode('-', $this->request->input('area'));
                    $min = $area_param[0];
                    $max = $area_param[1];
                    $query->whereBetween('area', [$min, $max]);
                }
                if ($this->request->input('sort')) {
                    $order_param = explode(':', $this->request->input('sort'));
                    $column = $order_param[0];
                    $direction = $order_param[1];
                    $query->orderBy($column, $direction);
                }
            }
        ));


        return $this->investment_room->floorRooms;
    }

    public function getInvestmentRoom()
    {
        return $this->investment_room;
    }
}
