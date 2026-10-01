<?php

namespace App\Http\Controllers\Admin\Slider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// CMS
use App\Models\Slider;
use App\Http\Requests\SliderFormRequest;
use App\Repositories\SliderRepository;
use App\Services\SliderService;

class IndexController extends Controller
{
    private $repository;
    private $service;

    public function __construct(SliderRepository $repository, SliderService $service)
    {
        $this->middleware('permission:slider-list|slider-create|slider-edit|slider-delete', [
            'only' => ['index','store']
        ]);
        $this->middleware('permission:slider-create', [
            'only' => ['create','store']
        ]);
        $this->middleware('permission:slider-edit', [
            'only' => ['edit','update']
        ]);
        $this->middleware('permission:slider-delete', [
            'only' => ['destroy']
        ]);

        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.slider.index', ['list' => $this->repository->allSort('ASC')]);
    }

    public function create()
    {
        return view('admin.slider.form', [
            'cardTitle' => 'Dodaj obrazek',
            'backButton' => route('admin.slider.index')
        ])->with('entry', Slider::make());
    }

    public function store(SliderFormRequest $request)
    {
        $slider = $this->repository->create($request->validated());

        if ($request->hasFile('file')) {
            $this->service->upload($request->title, $request->file('file'), $slider);
        }

        return redirect(route('admin.slider.index'))->with('success', 'Nowy obrazek dodany');
    }

    public function edit(int $id)
    {
        return view('admin.slider.form', [
            'entry' => $this->repository->find($id),
            'cardTitle' => 'Edytuj obrazek',
            'backButton' => route('admin.slider.index')
        ]);
    }

    public function update(SliderFormRequest $request, Slider $slider)
    {
        $this->repository->update($request->validated(), $slider);

        if ($request->hasFile('file')) {
            $this->service->upload($request->title, $request->file('file'), $slider, 1);
        }

        return redirect(route('admin.slider.index'))->with('success', 'Obrazek zaktualizowany');
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
