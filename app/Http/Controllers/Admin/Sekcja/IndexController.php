<?php

namespace App\Http\Controllers\Admin\Sekcja;

use App\Http\Controllers\Controller;
use App\Models\Sekcja;
use App\Services\SekcjaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Sekcje stron - stałe sekcje frontu edytowane w panelu albo z przycisku „Edytuj” na stronie
 * (modal ładuje formularz z akcji `formularz` i zapisuje AJAX-em do `update`).
 */
class IndexController extends Controller
{
    public function __construct(private SekcjaService $service)
    {
        $this->middleware('permission:sekcja-list|sekcja-edit', ['only' => ['index']]);
        $this->middleware('permission:sekcja-edit', ['only' => ['edit', 'formularz', 'update']]);
    }

    public function index()
    {
        $zapisane = Sekcja::all()->keyBy('klucz');

        $lista = collect(config('sekcje'))->map(fn ($schemat, $klucz) => (object) [
            'klucz' => $klucz,
            'nazwa' => $schemat['nazwa'],
            'strona' => $schemat['strona'] ?? null,
            'zmieniona' => optional($zapisane->get($klucz))->updated_at,
        ]);

        return view('admin.sekcja.index', ['lista' => $lista]);
    }

    public function edit(string $klucz)
    {
        return view('admin.sekcja.form', [
            'sekcja' => $this->sekcja($klucz),
            'backButton' => route('admin.sekcja.index'),
        ]);
    }

    /** Sam formularz - do modala na froncie */
    public function formularz(string $klucz)
    {
        return view('admin.sekcja.pola', ['sekcja' => $this->sekcja($klucz), 'modal' => true]);
    }

    public function update(Request $request, string $klucz)
    {
        $sekcja = $this->sekcja($klucz);

        $walidator = Validator::make(
            $request->all(),
            $this->service->reguly($sekcja),
            $this->service->komunikaty(),
            $this->service->nazwyPol($sekcja)
        );

        if ($walidator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['bledy' => $walidator->errors()->all()], 422);
            }

            return back()->withErrors($walidator)->withInput();
        }

        $this->service->zapisz($sekcja, $request);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.sekcja.index')->with('success', 'Zapisano sekcję: ' . $sekcja->nazwa());
    }

    private function sekcja(string $klucz): Sekcja
    {
        abort_unless(Sekcja::schematDla($klucz), 404);

        return Sekcja::firstOrNew(['klucz' => $klucz]);
    }
}
