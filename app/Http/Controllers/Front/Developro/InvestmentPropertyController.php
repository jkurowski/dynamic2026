<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Page;
use App\Models\Property;

class InvestmentPropertyController extends Controller
{
    private $pageId;

    public function __construct()
    {
        $this->pageId = 8;
    }


    #'/i/{slug}/{floor},{floorSlug}/{property},{propertySlug},{propertyFloor},{propertyRooms},{propertyArea}'
    public function index($lang, $slug, Floor $floor, $floorSlug, Property $property, $propertySlug)
    {
        $property->timestamps = false;
        $property->increment('views');
        $page = Page::where('id', $this->pageId)->first();
        $investment = Investment::findBySlug($slug);

        $areaSearch = $property->area_search;

        $similarProperties = Property::whereBetween('area_search', [$areaSearch - 5, $areaSearch + 5])
            ->where('id', '!=', $property->id)
            ->where('investment_id', '=', $investment->id)
            ->inRandomOrder()
            ->where('status', '=', 1)
            ->take(5)
            ->get();

        $property->with('relatedProperties');

        return view('front.developro.investment_property.index', [
            'investment' => $investment,
            'floor' => $floor,
            'property' => $property,
            'page' => $page,
            'next' => $property->findNext($investment->id, $property->number_order, $property->floor_id),
            'prev' => $property->findPrev($investment->id, $property->number_order, $property->floor_id),
            'similarProperties' => $similarProperties
        ]);
    }
}
