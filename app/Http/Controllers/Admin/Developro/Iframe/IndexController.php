<?php

namespace App\Http\Controllers\Admin\Developro\Iframe;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    
    public function index(Investment $investment)
    {
        $custom_css = $investment->iframe_css;

        return view('admin.developro.iframe.index', compact('investment', 'custom_css'));
    }

    public function store(Request $request, string $id) {
        $validated = $request->validate([
            'custom_css' => 'string|nullable'
        ]);

        $investment = Investment::findOrFail($id);
        $investment->iframe_css = $validated['custom_css'];
        $investment->save();

        return redirect()->route('admin.developro.investment.iframe.index', $id)->with('success', 'Zapisano zmiany');
     

    }
}
