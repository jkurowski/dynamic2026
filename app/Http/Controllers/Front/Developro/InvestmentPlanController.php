<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//CMS
use App\Repositories\InvestmentRepository;
use App\Models\Investment;
use App\Models\Page;

class InvestmentPlanController extends Controller
{
    private $repository;
    private $pageId;

    public function __construct(InvestmentRepository $repository)
    {
        $this->repository = $repository;
        $this->pageId = 8;
    }

    public function index($lang, Request $request, $slug)
    {
        $investment = Investment::withMin('pricesProperties as min_price', 'price_search')
            ->withMax('pricesProperties as max_price', 'price_search')
            ->where('slug', $slug)
            ->firstOrFail();


        // 🔥 wspólna funkcja sortowania
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

        $onlyActiveLocation = function ($query) {
            $query->where(function ($query) {
                $query->whereDoesntHave('building')
                    ->orWhereHas('building', fn($query) => $query->where('active', 1));
            })->where(function ($query) {
                $query->whereDoesntHave('floor')
                    ->orWhereHas('floor', fn($query) => $query->where('active', 1));
            });
        };

        // =========================
        // TYPE 1 – wiele budynków
        // =========================
        if ($investment->type == 1) {

            $investment_room = $investment->load([
                'buildingRooms' => function ($query) use ($request, $applySorting, $onlyActiveLocation) {

                    $query->orderBy('properties.highlighted', 'DESC');
                    $query->orderBy('properties.number_order', 'ASC');

                    $onlyActiveLocation($query);

                    if ($request->input('rooms')) {
                        $query->where('rooms', $request->input('rooms'));
                    }

                    if ($request->input('status')) {
                        $query->where('status', $request->input('status'));
                    }

                    if ($request->input('floor')) {
                        $query->where('floor_id', $request->input('floor'));
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

                    $applySorting($query);

                    $query->where('type', 1);
                },
                'plan'
            ]);

            $properties = $investment_room->buildingRooms;
        }

        // =========================
        // TYPE 2 – jeden budynek
        // =========================
        if ($investment->type == 2) {

            $investment_room = $investment->load([
                'floorRooms' => function ($query) use ($request, $investment, $applySorting, $onlyActiveLocation) {

                    $query->orderBy('properties.highlighted', 'DESC');
                    $query->orderBy('properties.number_order', 'ASC');

                    $onlyActiveLocation($query);

                    if ($request->input('rooms')) {
                        $query->where('rooms', $request->input('rooms'));
                    }

                    if ($request->input('status')) {
                        $query->where('status', $request->input('status'));
                    }

                    if ($request->input('floor')) {
                        $query->where('floor_id', $request->input('floor'));
                    }

                    if ($investment->show_properties == 3) {
                        $query->where('status', 1);
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

                    $applySorting($query);

                    $query->where('properties.type', 1);
                }
            ]);

            $properties = $investment_room->floorRooms;
        }

        // =========================
        // TYPE 3 – domy
        // =========================
        if ($investment->type == 3) {

            $investment_room = $investment->load([
                'properties' => function ($query) use ($request, $applySorting, $onlyActiveLocation) {

                    $onlyActiveLocation($query);

                    if ($request->input('rooms')) {
                        $query->where('rooms', $request->input('rooms'));
                    }

                    if ($request->input('status')) {
                        $query->where('status', $request->input('status'));
                    }

                    $applySorting($query);
                }
            ]);

            $properties = $investment_room->properties;
        }

        $page = Page::where('id', $this->pageId)->first();

        return view('front.developro.investment_plan.index', [
            'investment' => $investment,
            'properties' => $properties,
            'uniqueRooms' => $this->repository->getUniqueRooms($investment_room->properties),
            'page' => $page,
            'sort' => $request->input('sort')
        ]);
    }

    public function mockup($lang, $slug)
    {
        $investment = Investment::findBySlug($slug);

        $page = Page::where('id', $this->pageId)->first();

        return view('front.developro.investment_plan.mockup', [
            'investment' => $investment,
            'page' => $page
        ]);
    }
}


