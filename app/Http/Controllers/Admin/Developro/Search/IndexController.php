<?php

namespace App\Http\Controllers\Admin\Developro\Search;

use App\Http\Controllers\Controller;
use App\Repositories\InvestmentRepository;
use Illuminate\Http\Request;

// CMS
use App\Models\Investment;

class IndexController extends Controller
{
    private $repository;

    public function __construct(InvestmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Investment $investment, Request $request)
    {
        $properties = $this->repository->searchRooms($investment, $request);

        return view('admin.developro.search.index', [
            'investment' => $investment,
            'list' => $properties,
            'uniqueRooms' => $this->repository->getUniqueRooms($investment->properties),
        ]);
    }
}
