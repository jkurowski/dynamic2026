<?php

namespace App\Traits;

use App\Models\Investment;

trait InvestmentLinkTrait {

  public function getInvestmentLink(Investment $investment)
  {
    return route($this->getInvestmentRouteByType($investment->type), [$investment->id]);
  }
  public function getInvestmentRouteByType(int $investmentType)
  {
    switch ($investmentType) {
      case 1: // Osiedlowa
        return 'admin.developro.investment.buildings.index';
      case 2: // Budynkowa
        return 'admin.developro.investment.floors.index';
      case 3: // Z domami
        return 'admin.developro.investment.houses.index';
      default:
        return null;
    }
  }
}

