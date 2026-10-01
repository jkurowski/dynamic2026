<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PopupFormRequest;
use Spatie\Valuestore\Valuestore;

class PopupController extends Controller
{
    public function index()
    {
        return view('admin.settings.popup.index');
    }

    public function store(PopupFormRequest $request)
    {
        $settings = Valuestore::make(storage_path('app/settings.json'));
        $settings->put($request->except(['_token', 'submit']));

        return redirect(route('admin.settings.popup.index'))->with('success', 'Ustawienia zostały zapisane');
    }
}
