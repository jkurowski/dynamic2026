<?php

namespace App\Http\Controllers\Admin\Developro\House;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\PropertyFormRequest;
use App\Repositories\InvestmentRepository;
use App\Repositories\PropertyRepository;
use App\Services\PropertyService;

use App\Models\Investment;
use App\Models\Property;

class HouseController extends Controller
{
    private PropertyRepository $repository;
    private PropertyService $service;
    private InvestmentRepository $investmentRepository;
    public function __construct(PropertyRepository $repository, PropertyService $service, InvestmentRepository $investmentRepository)
    {
//        $this->middleware('permission:houses-list|houses-create|housesedit|houses-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:houses-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:houses-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:houses-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
        $this->service = $service;
        $this->investmentRepository = $investmentRepository;
    }

    public function index(Investment $investment)
    {
        return view('admin.developro.investment_house.index', ['investment' => $investment]);
    }

    public function create(Investment $investment)
    {
        return view('admin.developro.investment_house.form', [
            'cardTitle' => 'Dodaj dom',
            'backButton' => route('admin.developro.investment.houses.index', $investment),
            'investment' => $investment,
        ])->with('entry', Property::make());
    }

    public function store(PropertyFormRequest $request, Investment $investment)
    {
        $property = $this->repository->create(array_merge($request->validated(), [
            'investment_id' => $investment->id
        ]));

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $property);
        }

        if ($request->hasFile('file_pdf')) {
            $this->service->uploadPdf($request->name, $request->file('file_pdf'), $property);
        }

        return redirect(route('admin.developro.investment.houses.index', ['investment' => $investment]))->with('success', 'Dom poprawnie zapisany');
    }

    public function edit(Investment $investment, Property $house)
    {
        return view('admin.developro.investment_house.form', [
            'cardTitle' => 'Edytuj dom',
            'backButton' => route('admin.developro.investment.houses.index', $investment),
            'investment' => $investment,
            'entry' => $house
        ]);
    }

    public function update(PropertyFormRequest $request, Investment $investment, Property $house)
    {
        $this->repository->update($request->validated(), $house);

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $house, true);
        }

        if ($request->hasFile('file_pdf')) {
            $this->service->uploadPdf($request->name, $request->file('file_pdf'), $house, true);
        }

        $this->investmentRepository->sendMessageToInvestmentSupervisors($investment, 'Zmiana parametrów: '.$house->name);

        return redirect(route('admin.developro.investment.houses.index', ['investment' => $investment->id]))->with('success', 'Dom zaktualizowany');
    }

    public function destroy(Investment $investment, int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }
}
