<?php

namespace App\Http\Controllers\Admin\Developro\Popup;

use App\Http\Controllers\Controller;
use App\Http\Requests\PopupFormRequest;
use App\Models\Investment;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Investment $investment)
    {
        return view('admin.developro.investment_popup.index', ['investment' => $investment]);
    }

    public function update(PopupFormRequest $formRequest, Investment $investment)
    {
        $investment->update($formRequest->validated());

        return redirect(route('admin.developro.investment.popup.index', $investment))->with('success', 'Wpis zaktualizowany');
    }
}
