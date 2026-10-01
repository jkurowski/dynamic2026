<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Page;
use App\Repositories\FloorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// CMS

class InvestmentFloorController extends Controller
{
    private $repository;
    private $pageId;

    public function __construct(FloorRepository $repository)
    {
        $this->repository = $repository;
        $this->pageId = 8;
    }

    public function index($lang, $slug, Floor $floor, $floorSlug, Request $request)
    {
        $floor->loadMin('pricesProperties as min_price', 'price_search')
            ->loadMax('pricesProperties as max_price', 'price_search');

        $investment = Investment::findBySlug($slug);

        // 🔥 wspólne sortowanie
        $applySorting = function ($query) use ($request) {
            if ($request->filled('sort')) {

                // 🔥 usuń wszystkie wcześniejsze ORDER BY
                $query->reorder();

                $sorts = explode(',', $request->input('sort'));

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
            }
        };

        $investment_room = $investment->load([
            'floorRooms' => function ($query) use ($floor, $request, $applySorting) {

                $query->where('floor_id', $floor->id);

                // 🔥 custom order
                $customOrder = [1, 3, 2, 4, 5, 6];
                $orderList = implode(',', $customOrder);
                $query->orderByRaw("FIELD(properties.type, $orderList)");

                // 🔥 default order
                $query->orderBy('properties.highlighted', 'DESC');
                $query->orderBy('properties.number_order', 'ASC');

                if ($request->input('rooms')) {
                    $query->where('rooms', $request->input('rooms'));
                }

                if ($request->input('status')) {
                    $query->where('status', $request->input('status'));
                }

                if ($request->filled('area_min') || $request->filled('area_max') || $request->filled('area')) {

                    if ($request->filled('area_min') || $request->filled('area_max')) {
                        $min = (float) $request->input('area_min', 0);
                        $max = (float) $request->input('area_max', 500);

                        $query->whereBetween('area_search', [$min, $max]);

                    } elseif (str_contains($request->input('area'), '-')) {
                        [$min, $max] = explode('-', $request->input('area'));

                        $query->whereBetween('area_search', [(float)$min, (float)$max]);

                    } else {
                        $query->where('area_search', '>=', (float)$request->input('area'));
                    }
                }

                if ($request->filled('price_min') || $request->filled('price_max')) {
                    $min = (int) $request->input('price_min', 0);
                    $max = (int) $request->input('price_max', 5000000);

                    $query->whereBetween(
                        DB::raw('CAST(price_search AS UNSIGNED)'),
                        [$min, $max]
                    );
                }

                // 🔥 sort z request
                $applySorting($query);
            },

            'floor' => function ($query) use ($floor) {
                $query->where('id', $floor->id);
            }
        ]);

        $page = Page::where('id', $this->pageId)->first();
        $next_floor = $floor->findNext($investment->id, $floor->position, null);
        $prev_floor = $floor->findPrev($investment->id, $floor->position, null);

        return view('front.developro.investment_floor.index', [
            'investment' => $investment_room,
            'properties' => $investment_room->floorRooms,
            'uniqueRooms' => $this->repository->getUniqueRooms($floor->properties()),
            'next_floor' => $next_floor,
            'prev_floor' => $prev_floor,
            'floor' => $floor,
            'page' => $page,
            'sort' => $request->input('sort')
        ]);
    }
}
