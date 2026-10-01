<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Property;
use App\Models\RodoRules;

class InvestmentHouseController extends Controller
{
    public function index(Investment $investment, Property $property)
    {
        $property->increment('views');
        return view('front.investment_house.index', [
            'investment' => $investment,
            'property' => $property,
            'next_house' => $property->findNext($investment->id, $property->id),
            'prev_house' => $property->findPrev($investment->id, $property->id),
            'rules' => RodoRules::orderBy('sort')->whereStatus(1)->get()
        ]);
    }
}
