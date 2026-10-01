<?php

namespace App\Http\Controllers\Admin\Rodo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RodoSettings;

class SettingsController extends Controller
{

    public function index()
    {
        return view('admin.rodo_settings.index', ['entry' => RodoSettings::first()]);
    }

    public function update(Request $request, $id)
    {
        $settings = RodoSettings::find($id);
        $settings->update($request->only(['obligation', 'status']));
        return redirect()->route('admin.rodo.settings.index')->with('success', 'Ustawienia zapisane');
    }
}
