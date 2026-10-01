<?php

namespace App\Http\Controllers\Admin\Developro\Building;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyFormRequest;
use App\Jobs\EndPropertyPromotion;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Property;
use App\Models\PropertyProperty;
use App\Repositories\InvestmentRepository;
use App\Repositories\PropertyRepository;
use App\Services\PropertyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BuildingPropertyController extends Controller
{
    private PropertyRepository $repository;
    private PropertyService $service;

    private InvestmentRepository $investmentRepository;

    public function __construct(PropertyRepository $repository, PropertyService $service, InvestmentRepository $investmentRepository)
    {
//        $this->middleware('permission:box-list|box-create|box-edit|box-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:box-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:box-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:box-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
        $this->service = $service;
        $this->investmentRepository = $investmentRepository;
    }

    public function index(Investment $investment, Building $building, Floor $floor)
    {
        $list = $investment->load(array(
            'floorRooms' => function($query) use ($floor)
            {
                $query->where('floor_id', $floor->id);
            }
        ));

        return view('admin.developro.investment_building_property.index', [
            'investment' => $investment,
            'building' => $building,
            'floor' => $floor,
            'list' => $list,
            'count_property_status' => $list->floorRooms->countBy('status')
        ]);
    }

    public function create(Investment $investment, Building $building, Floor $floor)
    {
        $others = Property::where('investment_id', '=', $investment->id)
            ->where('status', '=', 1)
            ->whereNull('client_id')
            ->pluck('name', 'id');

        $all = Property::where('investment_id', $investment->id)
            ->get()
            ->mapWithKeys(function ($prop) {
                $name = $prop->name . ' (' . $prop->floor->name . ')';
                if ($prop->building && $prop->building->name) {
                    $name .= ' - ' . $prop->building->name;
                }

                return [$prop->id => $name];
            });

        return view('admin.developro.investment_building_property.form', [
            'cardTitle' => 'Dodaj powierzchnię',
            'backButton' => route('admin.developro.investment.building.floor.properties.index', [$investment, $building, $floor]),
            'floor' => $floor,
            'building' => $building,
            'investment' => $investment,
            'others' => $others,
            'all' => $all,
            'related' => collect()
        ])->with('entry', Property::make());
    }

    public function store(PropertyFormRequest $request, Investment $investment, Building $building, Floor $floor)
    {
        $property = $this->repository->create(array_merge($request->validated(), [
            'investment_id' => $investment->id,
            'building_id' => $building->id,
            'floor_id' => $floor->id
        ]));

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $property);
        }

        if ($request->hasFile('file_pdf')) {
            $this->service->uploadPdf($request->name, $request->file('file_pdf'), $property);
        }

        return redirect()->route('admin.developro.investment.building.floor.properties.index', [$investment, $building, $floor])->with('success', 'Powierzchnia zapisana');
    }

    public function edit(Investment $investment, Building $building, Floor $floor, Property $property)
    {
        // Get all properties for the investment except the current property
        $others = Property::where('investment_id', '=', $investment->id)
            ->where('id', '<>', $property->id)
            ->where('status', '=', 1)
            ->whereNull('client_id')
            ->pluck('name', 'id');

        $all = Property::where('investment_id', $investment->id)
            ->where('id', '<>', $property->id)
            ->get()
            ->mapWithKeys(function ($prop) {
                $name = $prop->name . ' (' . $prop->floor->name . ')';

                // Add building name if it exists
                if ($prop->building && $prop->building->name) {
                    $name .= ' - ' . $prop->building->name;
                }

                return [$prop->id => $name];
            });

        $related = $property->relatedProperties;

        $isRelated = PropertyProperty::where('related_property_id', $property->id)->exists();

        return view('admin.developro.investment_building_property.form', [
            'cardTitle' => 'Edytuj mieszkanie',
            'backButton' => route('admin.developro.investment.building.floor.properties.index', [$investment, $building, $floor]),
            'floor' => $floor,
            'building' => $building,
            'investment' => $investment,
            'entry' => $property,
            'others' => $others,
            'all' => $all,
            'related' => $related,
            'isRelated' => $isRelated
        ]);
    }

    public function update(PropertyFormRequest $request, Investment $investment, Building $building, Floor $floor, Property $property)
    {
        $this->repository->update($request->validated(), $property);

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $property, true);
        }

        if ($request->hasFile('file_pdf')) {
            $this->service->uploadPdf($request->name, $request->file('file_pdf'), $property, true);
        }

        // Dispatch the EndPropertyPromotion job if the promotion end date is set
        if ($request->filled('promotion_end_date') && $request->highlighted == 1) {
//            $promotionEndDate = $request->input('promotion_end_date');
//            $delay = now()->diffInSeconds($promotionEndDate, false);
//
//            if ($delay > 0) {  // Only dispatch if the end date is in the future
//                EndPropertyPromotion::dispatch($property)->delay($delay);
//            }

            $delay = now()->addSeconds(3600);  // Delay for 1 minute for testing
            EndPropertyPromotion::dispatch($property->id)->delay($delay);
        }

        $this->investmentRepository->sendMessageToInvestmentSupervisors($investment, 'Zmiana parametrów: '.$property->name);

        return redirect()->route('admin.developro.investment.building.floor.properties.index', [$investment, $building, $floor])->with('success', 'Powierzchnia zaktualizowana');
    }

    public function destroy(Investment $investment, Building $building, Floor $floor, Property $property)
    {
        $this->repository->delete($property->id);
        return response()->json('Deleted');
    }

    public function fetchProperties(Investment $investment) {
        $properties = $investment->selectProperties()->get();
        $types = $properties->groupBy('type');
        $result = [];
        foreach ($types as $type => $properties) {
            $result[$type] = $properties;
        }
        return response()->json($result);
    }

    public function storerelated(Request $request, $investmentId, $floorId, $propertyId)
    {
        $request->validate([
            'related_property_id' => 'required|exists:properties,id',
        ]);

        $related_id = $request->input('related_property_id');

        $isRelated = PropertyProperty::where('related_property_id', $related_id)->exists();
        $related_property = Property::findOrFail($related_id);

        if ($isRelated) {
            return getRelatedType($related_property->type);
        }

        $property = Property::findOrFail($propertyId);
        $property->relatedProperties()->attach($related_id);

        // Return a response
        return view('admin.developro.investment_shared.related', ['property' => $related_property]);
    }
}
