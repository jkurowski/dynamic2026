<?php

namespace App\Http\Controllers\Admin\Log;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\LogRepository;
use Illuminate\Http\Request;


class IndexController extends Controller
{

    private $repository;

    public function __construct(LogRepository $repository)
    {
        $this->middleware('permission:user-activity', [
            'only' => ['show']
        ]);

        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.log.index');
    }

    public function datatable(Request $request)
    {
        return $this->repository->getDataTable(null, $request->input('minDate'), $request->input('maxDate'));
    }

    public function show($id)
    {
        return view('admin.log.show', ['causer' => $id]);
    }
    public function datatableUser(User $causer, Request $request)
    {
        return $this->repository->getDataTable($causer, $request->input('minDate'), $request->input('maxDate'));
    }

}
