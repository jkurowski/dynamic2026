<?php

namespace App\Http\Controllers\Admin\Crm\CustomField;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomFieldFormRequest;
use App\Repositories\CustomFieldRepository;
use Illuminate\Http\Request;

//CMS

class IndexController extends Controller
{
    private $repository;

    public function __construct(CustomFieldRepository $repository)
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
    }

    public function index()
    {

        return view('admin.crm.custom-field.index', ['list' => $this->repository->all()]);
    }

    public function store(CustomFieldFormRequest $request)
    {
        $this->repository->create($request->validated());
        return response()->json(['list' => $this->repository->pluckGroup($request->group_id, ['id', 'value'])]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json(['status' => 'deleted'], 201);
    }
}
