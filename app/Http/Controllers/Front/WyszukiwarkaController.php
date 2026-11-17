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
        [$inwestycje, $pozostale] = Investment::naFroncie()->with('city')->get()
            ->partition(fn (Investment $i) => $i->maPodstrone());
        $inwestycje = $inwestycje->values();

        return view('front.inwestycje.wyszukiwarka', [
            'page' => Page::where('uri', 'wybierz-nieruchomosc')->first(),
            'inwestycje' => $inwestycje,
            // bez lokali w serwisie (strona inwestycji poza serwisem albo „wkrótce”) - pod wynikami, żeby lista
            // nie sugerowała, że w sprzedaży są tylko inwestycje z wyszukiwarki (uwaga klienta 10.11)
            'pozostale' => $pozostale->values(),
            'filtry' => FiltryOferty::dlaWyszukiwarki($inwestycje),
        ]);
    }
}
