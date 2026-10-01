<?php

namespace App\Helpers;

use App\Models\InvestmentCompany;
use App\Models\InvestmentSalePoint;

class InvestmentHelpers
{
    public static function getCompanies(): array
    {
        return InvestmentCompany::orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public static function getSalePoints(): array
    {
        return InvestmentSalePoint::orderBy('city')
            ->selectRaw("id, CONCAT(city, ', ', street) as full_address")
            ->pluck('full_address', 'id')
            ->toArray();
    }
}
