<?php

namespace App\Traits;

use App\Models\Property;
use Illuminate\Support\Facades\Log;

trait PropertyLinkTrait
{
    public function getLinkToProperty(Property $property)
    {
        $investmentType = $property->investment->type ?? null;

        if (is_null($investmentType)) {
            Log::warning('Missing investment type for property ID: ' . $property->id);
            return null;
        }

        ///admin.developro.investment.building.floor.others.edit
        ///admin.developro.investment.others.edit

        switch ($investmentType) {
            case 1:
                if (!$property->investment_id) {
                    Log::warning('Missing investment_id for property ID: ' . $property->id);
                }
                if (!$property->building || !$property->building->id) {
                    Log::warning('Missing building or building ID for property ID: ' . $property->id);
                }
                if (!$property->floor || !$property->floor->id) {
                    Log::warning('Missing floor or floor ID for property ID: ' . $property->id);
                }
                return route($this->getPropertyRouteByInvestmentType($investmentType, $property), [
                    $property->investment_id,
                    optional($property->building)->id,
                    optional($property->floor)->id,
                    $property->id
                ]);

            case 2:
                if (!$property->investment_id || !$property->floor || !$property->floor->id) {
                    Log::warning('Missing data for investment type 2, property ID: ' . $property->id, [
                        'investment_id' => $property->investment_id,
                        'floor' => $property->floor,
                    ]);
                }
                return route($this->getPropertyRouteByInvestmentType($investmentType), [
                    $property->investment_id,
                    optional($property->floor)->id,
                    $property->id
                ]);

            case 3:
                if (!$property->investment_id) {
                    Log::warning('Missing investment_id for property ID: ' . $property->id);
                }
                return route($this->getPropertyRouteByInvestmentType($investmentType), [
                    $property->investment_id,
                    $property->id
                ]);

            default:
                Log::warning('Unknown investment type for property ID: ' . $property->id);
                return null;
        }
    }

    public function getPropertyRouteByInvestmentType(int $investmentType, $property = null)
    {
        switch ($investmentType) {
            case 1: // Osiedlowa
                if ($property && $property->type == 1) {
                    return 'admin.developro.investment.building.floor.properties.edit';
                } else {
                    return 'admin.developro.investment.building.floor.others.edit';
                }

            case 2: // Budynkowa
                return 'admin.developro.investment.properties.edit';

            case 3: // Z domami
                return 'admin.developro.investment.houses.edit';

            default:
                return null;
        }
    }
}
