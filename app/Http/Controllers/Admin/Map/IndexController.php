<?php

namespace App\Http\Controllers\Admin\Map;

use App\Http\Controllers\Controller;

// CMS
use App\Models\Map;
use App\Repositories\MapRepository;
use App\Http\Requests\MapFormRequest;

class IndexController extends Controller
{
    private $repository;

    public function __construct(MapRepository $repository)
    {
//        $this->middleware('permission:map-list|map-create|map-edit|map-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:map-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:map-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:map-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.map.index', ['list' => $this->repository->all()]);
    }

    public function create()
    {
        return view('admin.map.form', [
            'cardTitle' => 'Dodaj punkt na mapie',
            'backButton' => route('admin.map.index')
        ])->with('entry', Map::make());
    }

    public function store(MapFormRequest $request)
    {
        $this->repository->create($request->validated());
        return redirect(route('admin.map.index'))->with('success', 'Nowy punkt dodany');
    }

    public function edit($id)
    {
        return view('admin.map.form', [
            'entry' => Map::find($id),
            'cardTitle' => 'Edytuj punkt',
            'backButton' => route('admin.map.index')
        ]);
    }

    public function update(MapFormRequest $request, int $id)
    {
        $map = $this->repository->find($id);
        $this->repository->update($request->validated(), $map);

        return redirect(route('admin.map.index'))->with('success', 'Punkt zaktualizowany');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }
}
