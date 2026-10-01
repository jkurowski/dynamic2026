<?php

namespace App\Http\Controllers\Admin\Developro\Page;

use App\Http\Controllers\Controller;

//CMS
use App\Models\Investment;
use App\Repositories\InvestmentPageRepository;
use App\Http\Requests\InvestPageFormRequest;
use App\Models\InvestmentPage;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private InvestmentPageRepository $repository;

    public function __construct(InvestmentPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Investment $investment)
    {
        return view('admin.developro.investment_page.index', ['list' => $this->repository->allPageSort('ASC', $investment->id), 'investment' => $investment]);
    }

    public function create(Investment $investment)
    {
        return view('admin.developro.investment_page.form', [
            'cardTitle' => 'Dodaj wpis',
            'investment' => $investment,
            'backButton' => route('admin.developro.investment.page.index', $investment)
        ])->with('entry', InvestmentPage::make());
    }

    public function store(InvestPageFormRequest $request, Investment $investment)
    {
        $this->repository->create($request->validated());
        return redirect(route('admin.developro.investment.page.index', $investment))->with('success', 'Nowy wpis dodany');
    }

    public function edit(Investment $investment, InvestmentPage $page)
    {

        return view('admin.developro.investment_page.form', [
            'entry' => $page,
            'investment' => $investment,
            'cardTitle' => 'Edytuj wpis',
            'backButton' => route('admin.developro.investment.page.index', $investment)
        ]);
    }

    public function update(InvestPageFormRequest $request, Investment $investment, InvestmentPage $page)
    {
        $this->repository->update($request->validated(), $page);
        return redirect(route('admin.developro.investment.page.index', $investment))->with('success', 'Wpis zaktualizowany');
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
