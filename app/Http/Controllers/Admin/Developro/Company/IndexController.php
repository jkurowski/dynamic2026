<?php

namespace App\Http\Controllers\Admin\Developro\Company;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\InvestmentCompanyFormRequest as FormRequest;
use App\Models\InvestmentCompany as Model;

class IndexController extends Controller
{
    protected string $listViewPath = 'admin.developro.investment_company.index';
    protected string $formViewPath = 'admin.developro.investment_company.form';
    protected string $routeIndex = 'admin.developro.investment-company.index';

    public function index()
    {
        $list = Model::all();
        return view($this->listViewPath, compact('list'));
    }

    public function create()
    {
        return view($this->formViewPath, [
            'cardTitle' => 'Dodaj wpis',
            'backButton' => route($this->routeIndex)
        ])->with('entry', Model::make());
    }

    public function store(FormRequest $request)
    {
        Model::create($request->validated());

        return redirect()
            ->route($this->routeIndex)
            ->with('success', 'Spółka została dodana.');
    }

    public function edit($id)
    {
        return view($this->formViewPath, [
            'entry' => Model::find($id),
            'cardTitle' => 'Edytuj wpis',
            'backButton' => route($this->routeIndex)
        ]);
    }

    public function update(FormRequest $request, $id)
    {
        $component = Model::findOrFail($id);
        $component->update($request->validated());

        return redirect()
            ->route($this->routeIndex)
            ->with('success', 'Składnik ceny został zaktualizowany.');
    }

    public function destroy(int $id)
    {
        $component = Model::findOrFail($id);
        $component->delete();

        return response()->json('Deleted');
    }
}
