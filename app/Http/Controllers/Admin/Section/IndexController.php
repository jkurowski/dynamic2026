<?php

namespace App\Http\Controllers\Admin\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// CMS
use App\Models\Section;
use App\Http\Requests\SectionFormRequest;
use App\Repositories\SectionRepository;
use App\Services\SectionService;

class IndexController extends Controller
{
    private $repository;
    private $service;

    public function __construct(SectionRepository $repository, SectionService $service)
    {
//        $this->middleware('permission:section-list|section-create|section-edit|section-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:section-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:section-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:section-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.section.index', ['list' => $this->repository->allSort('ASC')]);
    }

    public function create()
    {
        return view('admin.section.form', [
            'cardTitle' => 'Dodaj wpis',
            'backButton' => route('admin.section.index')
        ])->with('entry', Section::make());
    }

    public function store(SectionFormRequest $request)
    {
        $entry = $this->repository->create($request->validated());

        if ($request->hasFile('file')) {
            $this->service->upload($request->title, $request->file('file'), $entry);
        }

        return redirect(route('admin.section.index'))->with('success', 'Nowy wpis dodany');
    }

    public function edit(int $id)
    {
        return view('admin.section.form', [
            'entry' => $this->repository->find($id),
            'cardTitle' => 'Edytuj wpis',
            'backButton' => route('admin.section.index')
        ]);
    }

    public function update(SectionFormRequest $request, int $id)
    {
        $section = $this->repository->find($id);
        $this->repository->update($request->validated(), $section);

        if ($request->hasFile('file')) {
            $this->service->upload($request->title, $request->file('file'), $section, 1);
        }

        return redirect(route('admin.section.index'))->with('success', 'Wpis zaktualizowany');
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
