<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//CMS
use App\Models\Investment;
use App\Models\Property;
use App\Models\Page;

class SearchController extends Controller
{
    private int $pageId;
    public function __construct()
    {
        $this->pageId = 8;
    }

    public function index(Request $request)
    {
        $page = Page::find($this->pageId);

// Extract query parameters with default values
        $filters = $request->only([
            'city', 'rooms', 'area', 'advanced', 'invest', 'status',
            'kitchen', 'garden', 'price', 'type', 'sort',
            'area_min', 'area_max', 'price_min', 'price_max'
        ]);

// Provide default values for missing filters
        $filters = array_merge([
            'city' => null,
            'rooms' => null,
            'area' => null,
            'area_min' => null,
            'area_max' => null,
            'advanced' => null,
            'invest' => null,
            'status' => null,
            'kitchen' => null,
            'garden' => null,
            'price' => null,
            'price_min' => null,
            'price_max' => null,
            'type' => null,
            'sort' => null,
        ], $filters);

// Filter Investments
        $investments = Investment::query()
            ->where('status', 1)
            ->when($filters['city'], fn($query, $city) => $query->where('city_id', $city))
            ->when($filters['invest'], fn($query, $invest) => $query->where('id', $invest))
            ->when($filters['advanced'], function ($query, $advanced) {
                if (in_array($advanced, [1, 7])) {
                    // Strict match for 1 and 7
                    $query->where('progress', $advanced);
                } else {
                    // Show all progress values from the selected one upwards
                    $query->where('progress', '>=', $advanced);
                }
            })
            ->orderByDesc('progress') // Sort progress from high to low (7 → 1)
            ->get();


// Filter Properties
        $customOrder = [1, 3, 2, 4, 5, 6];
        $orderList = implode(',', $customOrder);

        $properties = Property::query()
            ->with(['building' => fn($query) => $query->select('id', 'active', 'name')])
            ->with(['floor' => fn($query) => $query->select('id', 'active', 'name')])
            ->with(['city' => fn($query) => $query->select('cities.id as city_id', 'cities.name')])
            ->where(function ($query) {
                $query->whereDoesntHave('building')
                    ->orWhereHas('building', fn($query) => $query->where('active', 1));
            })
            ->where(function ($query) {
                $query->whereDoesntHave('floor')
                    ->orWhereHas('floor', fn($query) => $query->where('active', 1));
            })
            ->when($filters['rooms'], fn($query, $rooms) => $query->where('rooms', $rooms))
            ->when(
                filled($filters['area']) || filled($filters['area_min']) || filled($filters['area_max']),
                function ($query) use ($filters) {
                    if ($filters['area_min'] || $filters['area_max']) {
                        $min = (float)($filters['area_min'] ?? 0);
                        $max = (float)($filters['area_max'] ?? 500);
                        $query->whereBetween('area_search', [$min, $max]);
                    } elseif (strpos($filters['area'], '-') !== false) {
                        [$min, $max] = explode('-', $filters['area']);
                        $query->whereBetween('area_search', [(float) $min, (float) $max]);
                    } else {
                        $query->where('area_search', '>=', (float) $filters['area']);
                    }
                })
            ->when(
                filled($filters['price']) || filled($filters['price_min']) || filled($filters['price_max']),
                function ($query) use ($filters) {

                    $min = (int)($filters['price_min'] ?? 0);
                    $max = (int)($filters['price_max'] ?? 5000000);

                    $query->whereBetween(
                        DB::raw('CAST(price_search AS UNSIGNED)'),
                        [$min, $max]
                    );
                }
            )
            ->when($filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($filters['kitchen'], fn($query, $kitchen) => $query->where('kitchen', $kitchen))

            ->when(filled($filters['garden']) && $filters['garden'] == 1, fn($query) =>
            $query->whereNotNull('garden_area')->where('garden_area', '!=', '')
            )
            ->when(filled($filters['garden']) && $filters['garden'] == 2, function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('garden_area')
                        ->orWhere('garden_area', '');
                });
            })

            ->when($filters['type'], fn($query, $type) => $query->where('type', $type))
            ->when($filters['sort'], function ($query, $sort) {
                $sorts = explode(',', $sort);
                foreach ($sorts as $s) {
                    switch ($s) {
                        case 'area_asc':
                            $query->orderBy('area_search', 'asc');
                            break;
                        case 'area_desc':
                            $query->orderBy('area_search', 'desc');
                            break;
                        case 'price_asc':
                            $query->orderByRaw('CAST(price_brutto AS UNSIGNED) ASC');
                            break;
                        case 'price_desc':
                            $query->orderByRaw('CAST(price_brutto AS UNSIGNED) DESC');
                            break;
                        case 'views_asc':
                            $query->orderBy('views', 'asc');
                            break;
                        case 'views_desc':
                            $query->orderBy('views', 'desc');
                            break;
                    }
                }
            })
            ->orderByRaw("FIELD(properties.type, $orderList)")
            ->orderBy('properties.highlighted', 'DESC')
            ->orderBy('properties.number_order', 'ASC')
            ->get();

        // Group properties by investment
        $results = $investments->map(function ($investment) use ($properties) {
            return [
                'investment' => $investment,
                'properties' => $properties->where('investment_id', $investment->id),
            ];
        });

        return view('front.developro.search.index', [
            'page' => $page,
            'results' => $results,
            'sort' => $filters['sort']
        ]);
    }
}
