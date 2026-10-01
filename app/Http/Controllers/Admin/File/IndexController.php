<?php

namespace App\Http\Controllers\Admin\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// CMS
use App\Models\File;
use App\Http\Requests\FileFormRequest;
use App\Repositories\FileRepository;
use App\Services\FileService;

class IndexController extends Controller
{
    private $repository;
    private $service;

    public function __construct(FileRepository $repository, FileService $service)
    {
//        $this->middleware('permission:file-list|file-create|file-edit|file-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:file-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:file-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:file-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(Request $request)
    {
        //File::fixTree();
        if ($request->input('type') == 'modal'){
            return view('admin.file.modal.index', ['list' => $this->repository->allParentNull()]);
        } else {
            return view('admin.file.index', ['list' => $this->repository->allParentNull()]);
        }
    }

    public function create(File $file)
    {
        if ($file->exists) {
            return view('admin.file.file-form', [
                'cardTitle' => $file->name . ' - Dodaj plik',
                'parent_id' => $file->id,
                'backButton' => route('admin.file-catalog.show', $file)
            ])->with('entry', File::make());
        } else {
            return view('admin.file.file-form', [
                'cardTitle' => 'Dodaj plik',
                'backButton' => route('admin.file.index')
            ])->with('entry', File::make());
        }
    }

    public function store(FileFormRequest $request)
    {

        $entry = $this->repository->create($request->validated());

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $entry);
        }

        if ($request->isNotFilled('parent_id')) {
            return redirect(route('admin.file.index'))->with('success', 'Nowy plik dodany');
        } else {
            return redirect(route('admin.file-catalog.show', $request->parent_id))->with('success', 'Nowy plik dodany');
        }
    }

    public function edit(int $id)
    {
        return view('admin.file.file-form', [
            'entry' => $this->repository->find($id),
            'cardTitle' => 'Edytuj plik',
            'backButton' => route('admin.file.index')
        ]);
    }

    public function update(FileFormRequest $request, int $id)
    {
        $entry = $this->repository->find($id);
        $this->repository->update($request->validated(), $entry);

        if ($request->hasFile('file')) {
            $this->service->upload($request->name, $request->file('file'), $entry, 1);
        }

        if ($entry->parent_id) {
            return redirect(route('admin.file-catalog.show', $entry->parent_id))->with('success', 'Wpis zaktualizowany');
        } else {
            return redirect(route('admin.file.index'))->with('success', 'Wpis zaktualizowany');
        }
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }

    public function download(File $file) {
        $file->increment('download');
        return redirect('/uploads/storage/'. $file->file);
    }
}
