<?php

namespace App\Http\Controllers\Admin\Rodo;

use App\Http\Controllers\Controller;
use App\Http\Requests\RodoRulesFormRequest;
use Illuminate\Http\Request;

use App\Repositories\RodoRulesRepository;
use App\Models\RodoRules;

class RulesController extends Controller
{
    private $repository;

    public function __construct(RodoRulesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.rodo_rules.index', ['list' => $this->repository->allSort('ASC')]);
    }

    public function create()
    {
        return view('admin.rodo_rules.form', [
            'cardTitle' => 'Dodaj regułkę',
            'backButton' => route('admin.rodo.rules.index')
        ])->with('entry', RodoRules::make());
    }

    public function store(RodoRulesFormRequest $request)
    {
        $this->repository->create($request->validated());
        return redirect(route('admin.rodo.rules.index'))->with('success', 'Nowa regułka dodana');
    }

    public function edit(int $id)
    {
        return view('admin.rodo_rules.form', [
            'entry' => RodoRules::find($id),
            'cardTitle' => 'Edytuj regułkę',
            'backButton' => route('admin.rodo.rules.index')
        ]);
    }

    public function update(RodoRulesFormRequest $request, RodoRules $rule)
    {
        $this->repository->update($request->validated(), $rule);
        return redirect(route('admin.rodo.rules.index'))->with('success', 'Regułka zaktualizowana');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }
}
