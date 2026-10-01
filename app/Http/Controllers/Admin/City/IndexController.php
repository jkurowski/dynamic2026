<?php

namespace App\Http\Controllers\Admin\City;

use App\Http\Controllers\Controller;
use App\Services\CityService;
use Illuminate\Http\Request;

// CMS
use App\Http\Requests\CityFormRequest;
use App\Repositories\CityRepository;
use App\Models\City;

class IndexController extends Controller
{
    private CityRepository $repository;
    private CityService $service;
    public function __construct(CityRepository $repository, CityService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.city.index', ['list' => $this->repository->allSort('ASC')]);
    }

    public function create()
    {
        return view('admin.city.form', [
            'cardTitle' => 'Dodaj wpis',
            'backButton' => route('admin.city.index')
        ])->with('entry', City::make());
    }

    public function store(CityFormRequest $request)
    {
        $city = $this->repository->create($request->validated());

        if ($request->hasFile('header')) {
            $this->service->uploadHeader($request->name, $request->file('header'), $city);
        }

        return redirect(route('admin.city.index'))->with('success', 'Nowy wpis dodany');
    }

    public function edit(City $city)
    {
        if(request()->get('lang')) {
            app()->setLocale(request()->get('lang'));
        }

        return view('admin.city.form', [
            'entry' => $city,
            'cardTitle' => 'Edytuj wpis',
            'backButton' => route('admin.city.index')
        ]);
    }

    public function update(CityFormRequest $request, City $city)
    {
        if(request()->get('lang')) {
            app()->setLocale(request()->get('lang'));
        }

        $this->repository->update($request->validated(), $city);

        if ($request->hasFile('header')) {
            $this->service->uploadHeader($request->name, $request->file('header'), $city, true);
        }

        return redirect(route('admin.city.index'))->with('success', 'Wpis zaktualizowany');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }

    public function sort(Request $request)
    {
        $this->repository->updateOrder($request->get('recordsArray'));
    }
}
