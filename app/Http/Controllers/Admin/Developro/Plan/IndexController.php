<?php

namespace App\Http\Controllers\Admin\Developro\Plan;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Repositories\InvestmentRepository;
use App\Services\InvestmentService;
use Illuminate\Http\Request;

// CMS

class IndexController extends Controller
{
    private InvestmentService $service;
    private InvestmentRepository $repository;

    public function __construct(InvestmentService $service, InvestmentRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(Investment $investment)
    {
        $investment->load('plan');
        return view('admin.developro.investment_plan.index', ['investment' => $investment]);
    }

    public function store(Request $request, Investment $investment)
    {
        if ($request->hasFile('qqfile')) {
            $this->service->uploadPlan($investment, $request->file('qqfile'));
        }

        $this->repository->sendMessageToInvestmentSupervisors($investment, 'Zmiana planu');

        return response()->json(['success' => true]);
    }
}
