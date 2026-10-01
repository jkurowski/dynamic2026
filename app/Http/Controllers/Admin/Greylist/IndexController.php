<?php

namespace App\Http\Controllers\Admin\Greylist;

use App\Http\Controllers\Controller;

// CMS
use App\Repositories\GreylistRepository;
use App\Http\Requests\GreylistFormRequest;

use App\Models\Greylist;

class IndexController extends Controller
{

    private $repository;

    public function __construct(GreylistRepository $repository)
    {
//        $this->middleware('permission:grey-list|grey-create|grey-edit|grey-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:grey-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:grey-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:grey-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.greylist.index', ['list' => $this->repository->idDesc()]);
    }

    public function create()
    {
        return view('admin.greylist.form', [
            'cardTitle' => 'Dodaj adres IP',
            'backButton' => route('admin.greylist.index')
        ])->with('entry', Greylist::make());
    }


    public function store(GreylistFormRequest $request)
    {
        $this->repository->create($request->validated());
        return redirect(route('admin.greylist.index'))->with('success', 'Nowy adres IP dodany');
    }

    public function edit($id)
    {
        return view('admin.greylist.form', [
            'entry' => Greylist::find($id),
            'cardTitle' => 'Edytuj adres IP',
            'backButton' => route('admin.greylist.index')
        ]);
    }

    public function update(GreylistFormRequest $request, Greylist $greylist)
    {
        $this->repository->update($request->validated(), $greylist);
        return redirect(route('admin.greylist.index'))->with('success', 'Adres IP zaktualizowany');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }
}
