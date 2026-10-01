<?php

namespace App\Http\Controllers\Admin\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// CMS
use App\Models\File;
use App\Http\Requests\FileFormRequest;
use App\Repositories\FileCatalogRepository;

class CatalogController extends Controller
{
    private $repository;

    public function __construct(FileCatalogRepository $repository)
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
    }

    public function create(File $file)
    {
        if($file->exists) {
            return view('admin.file.catalog-form', [
                'cardTitle' => $file->name . ' - Dodaj katalog',
                'parent_id' => $file->id,
                'backButton' => route('admin.file-catalog.show', $file)
            ])->with('entry', File::make());
        } else {
            return view('admin.file.catalog-form', [
                'cardTitle' => 'Dodaj katalog',
                'backButton' => route('admin.file.index')
            ])->with('entry', File::make());
        }
    }

    public function store(FileFormRequest $request)
    {
        $this->repository->create(array_merge($request->validated(), [
            'type' => 2,
            'mime' => 'catalog'
        ]));

        if ($request->input('parent_id') == 0) {
            return redirect(route('admin.file.index'))->with('success', 'Nowy katalog dodany');
        } else {
            return redirect(route('admin.file-catalog.show', $request->input('parent_id')))->with('success', 'Nowy katalog dodany');
        }
    }

    public function edit(int $id)
    {
        $entry = $this->repository->find($id);
        return view('admin.file.catalog-form', [
            'entry' => $entry,
            'parent_id' => $entry->parent_id,
            'cardTitle' => 'Edytuj katalog',
            'backButton' => route('admin.file.index')
        ]);
    }

    public function update(FileFormRequest $request, int $id)
    {
        $entry = $this->repository->find($id);
        $this->repository->update($request->validated(), $entry);

        if ($request->input('parent_id') == 0) {
            return redirect(route('admin.file.index'))->with('success', 'Wpis zaktualizowany');
        } else {
            return redirect(route('admin.file-catalog.show', $request->input('parent_id')))->with('success', 'Wpis zaktualizowany');
        }
    }

    public function show(Request $request, int $id)
    {
        if ($request->input('type') == 'modal'){
            return view('admin.file.modal.show', [
                'list' => $this->repository->allParent($id),
                'entry' => $this->repository->find($id)
            ]);
        } else {
            return view('admin.file.show', [
                'list' => $this->repository->allParent($id),
                'entry' => $this->repository->find($id)
            ]);
        }
    }

}
