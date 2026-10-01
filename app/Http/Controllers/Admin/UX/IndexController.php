<?php

namespace App\Http\Controllers\Admin\UX;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Repositories\InvestmentRepository;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private $repository;

    public function __construct(InvestmentRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index()
    {
        return view('admin.ux.index', ['list' => $this->repository->all()]);
    }

    public function properties(Request $request)
    {
        return Property::where('investment_id', $request->get('id'))->pluck('name', 'id');
    }
}
