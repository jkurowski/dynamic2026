<?php

namespace App\Http\Controllers\Admin\Developro\PropertyPrice;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\PropertyPriceComponentFormRequest as FormRequest;
use App\Models\PropertyPriceComponent as Model;

class IndexController extends Controller
{
    protected string $formViewPath = 'admin.developro.investment_property_price_components.form';
    protected string $routeIndex = 'admin.developro.property-price-components.index';

    public function index()
    {
        $list = Model::all();
        return view('admin.developro.investment_property_price_components.index', compact('list'));
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
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:property_price_components,name',
        ]);

        Model::create($validated);

        return redirect()
            ->route($this->routeIndex)
            ->with('success', 'Składnik ceny został dodany.');
    }

    public function edit(int $id)
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

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:property_price_components,name,' . $component->id,
        ]);

        $component->update($validated);

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
