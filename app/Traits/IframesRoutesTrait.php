<?php

namespace App\Traits;

use App\Models\Investment;
use App\Models\Property;

trait IframesRoutesTrait
{

    public function getRouteForProperty(Investment $investment, Property $property)
    {

        switch ($investment->type) {
            case 2:

                return route('front.iframe.single.apartmentBuilding', ['investment' => $investment->slug, 'floor' => $property->floor_id, 'property' => $property->id]);

            case 3:
                return route('front.iframe.single', ['investment' => $investment->slug, 'property' => $property->id]);

            default:
                return '#';
        }
    }
}
