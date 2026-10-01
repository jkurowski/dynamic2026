<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;

use App\Models\Investment;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Page;
use App\Models\Property;
use App\Models\RodoRules;


class InvestmentBuildingPropertyController extends Controller
{
    private $pageId;

    public function __construct()
    {
        $this->pageId = 8;
    }

    public function index($lang, $slug, Building $building, $buildingSlug, Floor $floor, $floorSlug, Property $property)
    {
        $property->timestamps = false;
        $property->increment('views');

        $investment = Investment::findBySlug($slug);
        $page = Page::where('id', $this->pageId)->first();

        $areaSearch = $property->area_search;

        $similarProperties = Property::whereBetween('area_search', [$areaSearch - 5, $areaSearch + 5])
            ->where('id', '!=', $property->id)
            ->where('status', '=', 1)
            ->where('investment_id', '=', $investment->id)
            ->where('building_id', '=', $building->id)
            ->inRandomOrder()
            ->take(5)
            ->get();


        return view('front.developro.investment_property.index', [
            'page' => $page,
            'investment' => $investment,
            'building' => $building,
            'floor' => $floor,
            'next' => $property->findNext($investment->id, $property->number_order, $property->floor_id, $building->id),
            'prev' => $property->findPrev($investment->id, $property->number_order, $property->floor_id, $building->id),
            'property' => $property,
            'similarProperties' => $similarProperties
        ]);
    }
}
