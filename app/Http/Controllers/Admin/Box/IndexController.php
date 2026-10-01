<?php

namespace App\Http\Controllers\Admin\Box;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// CMS
use App\Http\Requests\BoxFormRequest;
use App\Repositories\BoxRepository;
use App\Services\BoxService;
use App\Models\Boxes;

class IndexController extends Controller
{
    private BoxRepository $repository;
    private BoxService $service;

    public function __construct(BoxRepository $repository, BoxService $service)
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
    }

    public function index()
    {
        return view('admin.box.index', ['list' => $this->repository->allSort('ASC')]);
    }

    public function create()
    {
        return view('admin.box.form', [
            'cardTitle' => 'Dodaj boks',
            'backButton' => route('admin.box.index')
        ])->with('entry', Boxes::make());
    }

    public function store(BoxFormRequest $request)
    {
        $entry = $this->repository->create($request->validated());

        if ($request->hasFile('file')) {
            $this->service->upload($request->title, $request->file('file'), $entry);
        }

        return redirect(route('admin.box.index'))->with('success', 'Nowy boks dodany');
    }

    public function edit(int $id)
    {
        return view('admin.box.form', [
            'entry' => $this->repository->find($id),
            'cardTitle' => 'Edytuj boks',
            'backButton' => route('admin.box.index')
        ]);
    }

    public function update(BoxFormRequest $request, int $id)
    {
        $box = $this->repository->find($id);
        $this->repository->update($request->validated(), $box);

        if ($request->hasFile('file')) {
            $this->service->upload($request->title, $request->file('file'), $box, 1);
        }

        return redirect(route('admin.box.index'))->with('success', 'Boks zaktualizowany');
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
