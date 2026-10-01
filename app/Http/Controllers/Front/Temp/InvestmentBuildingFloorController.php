<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Models\Investment;
use App\Models\Building;
use App\Models\Floor;
use Illuminate\Http\Request;

class InvestmentBuildingFloorController extends Controller
{
    public function index(Investment $investment, Building $building, Floor $floor, Request $request)
    {
        $investment_room = $investment->load(array(
            'buildingRooms' => function($query) use ($building, $floor, $request)
            {
                $query->where('properties.floor_id', $floor->id);
                $query->where('properties.building_id', $building->id);

                if ($request->input('rooms')) {
                    $query->where('rooms', $request->input('rooms'));
                }
                if ($request->input('status')) {
                    $query->where('status', $request->input('status'));
                }
                if ($request->input('sort')) {
                    $order_param = explode(':', $request->input('sort'));
                    $column = $order_param[0];
                    $direction = $order_param[1];
                    $query->orderBy($column, $direction);
                }
            },
            'building' => function($query) use ($building)
            {
                $query->where('id', $building->id);
            },
            'floor' => function($query) use ($floor)
            {
                $query->where('id', $floor->id);
            }
        ));

        return view('front.investment_building_floor.index', [
            'investment' => $investment_room,
            'properties' => $investment->buildingRooms,
            'next_floor' => $floor->findNext($investment->id, $building->id, $floor->id),
            'prev_floor' => $floor->findPrev($investment->id, $building->id, $floor->id)
        ]);
    }

}
