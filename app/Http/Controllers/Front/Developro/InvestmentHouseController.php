<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Property;
use App\Models\RodoRules;

class InvestmentHouseController extends Controller
{
    public function index($slug, Property $property)
    {
        $investment = Investment::findBySlug($slug);

        // Check if the property exists in the investment's properties
        if (!$investment->properties->contains($property)) {
            abort(404, 'Property not found in the specified investment.');
        }

        return view('front.developro.investment_house.index', [
            'investment' => $investment,
            'property' => $property,
            'next_house' => $property->findNext($investment->id, $property->number_order),
            'prev_house' => $property->findPrev($investment->id, $property->number_order),
            'rules' => RodoRules::orderBy('sort')->whereActive(1)->get()
        ]);
    }
}
