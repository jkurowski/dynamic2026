<?php

namespace App\Http\Controllers\Front\Developro\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Repositories\InvestmentRepository;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private InvestmentRepository $repository;

    public function __construct(InvestmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function json($lang, $slug)
    {
        $investment = $this->repository->findBySlug($slug);

        $properties = Property::with('investment:id,name')
            ->with('floor:id,name')
            ->with('building:id,name')
            ->select(
                'id',
                'name',
                'investment_id',
                'floor_id',
                'building_id',
                'number',
                'status',
                'area',
                'rooms',
                'garden_area',
                'balcony_area',
                'balcony_area_2',
                'price_brutto',
                'promotion_price',
                'file',
                'file_pdf'
            )
            ->where('investment_id', $investment->id)
            ->get();

        $properties = $properties->makeHidden(['investment_id', 'floor_id', 'building_id']);

        return response()->json($properties);
    }
}
