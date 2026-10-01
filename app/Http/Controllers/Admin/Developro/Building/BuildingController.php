<?php

namespace App\Http\Controllers\Admin\Developro\Building;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\BuildingFormRequest;
use App\Repositories\BuildingRepository;
use App\Services\BuildingService;

use App\Models\Investment;
use App\Models\Building;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{
    private BuildingRepository $repository;
    private BuildingService $service;

    public function __construct(BuildingRepository $repository, BuildingService $service)
    {
//        $this->middleware('permission:building-list|building-create|building-edit|building-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:building-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:building-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:building-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(Investment $investment)
    {
        return view('admin.developro.investment_building.index', ['investment' => $investment]);
    }

    public function create(Investment $investment)
    {
        return view('admin.developro.investment_building.form', [
            'cardTitle' => 'Dodaj budynek',
            'backButton' => route('admin.developro.investment.buildings.index', $investment),
            'investment' => $investment,
        ])->with('entry', Building::make());
    }

    public function store(BuildingFormRequest $request, Investment $investment)
    {
        $building = $this->repository->create($request->validated());

        if ($request->hasFile('file')) {
            $this->service->uploadPlan($request->name, $request->file('file'), $building, $investment->id);
        }

        return redirect()->route('admin.developro.investment.buildings.index', $investment)->with('success', 'Budynek zapisany');
    }

    public function edit(Investment $investment, Building $building)
    {

        return view('admin.developro.investment_building.form', [
            'cardTitle' => 'Edytuj budynek',
            'backButton' => route('admin.developro.investment.buildings.index', $investment),
            'investment' => $investment->load('plan'),
            'entry' => $building
        ]);
    }

    public function update(BuildingFormRequest $request, Investment $investment, Building $building)
    {

        $this->repository->update($request->validated(), $building);

        if ($request->hasFile('file')) {
            $this->service->uploadPlan($request->name, $request->file('file'), $building, $investment->id, true);
        }

        return redirect()->route('admin.developro.investment.buildings.index', $investment)->with('success', 'Budynek zaktualizowany');
    }

    public function destroy(Investment $investment, Building $building)
    {
        $this->repository->delete($building->id);
        return response()->json('Deleted');
    }

    public function convert(){
        // Fetch data from the old table
        $old = DB::connection('old_mysql')->table('inwestycje_budynki')->orderBy('id')->get();

        // Define the default locale
        $defaultLocale = 'pl';

        foreach ($old as $o) {

            $entry = new Building();

            $entry->fill([
                'investment_id' => $o->id_inwest ?? null,
                'name' => $o->nazwa ?? null,
                'slug' => $o->tag ?? null,
                'number' => $o->numer ?? null,
                'html' => $o->html ?? null,
                'cords' => $o->cords ?? null,
                'area_range' => $o->zakres_powierzchnia ?? null,
                'room_range' => $o->zakres_pokoje ?? null,
                'price_range' => $o->zakres_cen ?? null,
                'file' => $o->plik ?? null,
                'sort' => $o->sort ?? 0,
                'meta_robots' => null,
                'active' => 1,
            ]);

            $entry->setTranslation('name', $defaultLocale, $o->nazwa);
            $entry->setTranslation('meta_title', $defaultLocale, $o->meta_tytul);
            $entry->setTranslation('meta_description', $defaultLocale, $o->meta_opis);
            $entry->save();
        }

        return redirect(route('admin.developro.investment.index'))->with('success', 'Budynki przetłumaczone');
    }
}
