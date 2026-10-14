<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Page;
use App\Support\FiltryOferty;

/**
 * „Wybierz nieruchomość” - centralny widok oferty ze wszystkich inwestycji (szablon wyszukiwarka.html),
 * wyniki pogrupowane po inwestycjach. Filtry, sortowanie i schowek (?ulubione=1) działają w przeglądarce
 * (public/js/glowny.js) na danych z atrybutów data-* wierszy - lokali jest kilkadziesiąt, nie trzeba stronicować.
 * Parametry ze strony głównej (?inwestycja=...&pokoje=...) ustawiają filtry po wejściu.
 */
class WyszukiwarkaController extends Controller
{
    public function index()
    {
        $inwestycje = Investment::naFroncie()->with('city')->get()
            ->filter(fn (Investment $i) => $i->maPodstrone())
            ->values();

        return view('front.inwestycje.wyszukiwarka', [
            'page' => Page::where('uri', 'wybierz-nieruchomosc')->first(),
            'inwestycje' => $inwestycje,
            'filtry' => FiltryOferty::dlaWyszukiwarki($inwestycje),
        ]);
    }
}
