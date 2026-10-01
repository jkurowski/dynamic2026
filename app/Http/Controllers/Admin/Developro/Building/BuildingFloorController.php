<?php

namespace App\Http\Controllers\Admin\Developro\Building;

use App\Http\Controllers\Controller;

// CMS
use App\Repositories\BuildingFloorRepository;
use App\Http\Requests\FloorFormRequest;
use App\Services\BuildingFloorService;

use App\Models\Investment;
use App\Models\Building;
use App\Models\Floor;
use Illuminate\Http\Request;


class BuildingFloorController extends Controller
{
    private $repository;
    private $service;

    public function __construct(BuildingFloorRepository $repository, BuildingFloorService $service)
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

    public function index(Investment $investment, Building $building)
    {
        return view('admin.developro.investment_building_floor.index', [
            'investment' => $investment,
            'building' => $building,
            'list' => $building->floorsWithCount()
        ]);
    }

    public function create(Investment $investment, Building $building)
    {
        return view('admin.developro.investment_building_floor.form', [
            'cardTitle' => 'Dodaj pietro',
            'backButton' => route('admin.developro.investment.building.floors.index', [$investment, $building]),
            'building' => $building,
            'investment' => $investment,
        ])->with('entry', Building::make());
    }

    public function store(FloorFormRequest $request, Investment $investment, Building $building)
    {

        $floor = $this->repository->create($request->validated());

        if ($request->hasFile('file')) {
            $this->service->uploadPlan($request->name, $request->file('file'), $floor);
        }

        return redirect()->route('admin.developro.investment.building.floors.index', [$investment, $building])->with('success', 'Nowe piętro dodane');
    }

    public function edit(Investment $investment, Building $building, Floor $floor)
    {
        return view('admin.developro.investment_building_floor.form', [
            'cardTitle' => 'Edytuj pietro',
            'backButton' => route('admin.developro.investment.building.floors.index', [$investment, $building]),
            'entry' => $floor,
            'building' => $building,
            'investment' => $investment
        ]);
    }

    public function update(FloorFormRequest $request, Investment $investment, Building $building, Floor $floor)
    {
        $this->repository->update($request->validated(), $floor);

        if ($request->hasFile('file')) {
            $this->service->uploadPlan($request->name, $request->file('file'), $floor, true);
        }

        return redirect()->route('admin.developro.investment.building.floors.index', [$investment, $building])->with('success', 'Pietro zaktualizowane');
    }

    public function copy(Investment $investment, Building $building, Floor $floor)
    {
        $newFloor = $floor->replicate();
        $newFloor->html = '';
        $newFloor->cords = '';
        $newFloor->file = '';
        $newFloor->file_webp = '';
        $newFloor->number = $floor->number + 1;
        $newFloor->position = $floor->position + 1;
        $newFloor->save();
        return redirect()->route('admin.developro.investment.building.floors.index', [$investment, $building])->with('success', 'Pietro skopiowane');
    }

    public function destroy(Investment $investment, Building $building, Floor $floor)
    {
        $this->repository->delete($floor->id);
        return response()->json('Deleted');
    }
}
