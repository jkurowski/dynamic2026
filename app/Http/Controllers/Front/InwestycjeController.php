<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Page;
use App\Models\Property;
use App\Support\FiltryOferty;
use Illuminate\Support\Facades\View;

/**
 * Inwestycje na nowym froncie (szablon dynamic-front: inwestycje.html, inwestycja.html, lokal.html).
 *   /inwestycje                          lista inwestycji w sprzedaży
 *   /inwestycje/{inwestycja}             podstrona: hero, lokale (kafelki/lista + filtry), treści inwestycji
 *   /inwestycje/{inwestycja}/{id-nazwa}  karta lokalu
 * Treści podstrony (opis, okolica, mapa okolicy, atuty, galeria) na razie statyczne w
 * front/inwestycje/tresci/{slug}.blade.php - formularze w panelu dojdą po demo.
 */
class InwestycjeController extends Controller
{
    public function index()
    {
        return view('front.inwestycje.index', [
            'page' => Page::where('uri', 'inwestycje')->first(),
            'inwestycje' => Investment::wSprzedazy()->with('city')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $inwestycja = $this->inwestycja($slug);
        $lokale = $inwestycja->lokaleNaFroncie();

        return view('front.inwestycje.show', [
            'page' => Page::where('uri', 'inwestycje')->first(),
            'inwestycja' => $inwestycja,
            'lokale' => $lokale,
            'filtry' => FiltryOferty::dlaInwestycji($lokale),
            'tresci' => View::exists('front.inwestycje.tresci.' . $slug) ? 'front.inwestycje.tresci.' . $slug : null,
        ]);
    }

    public function lokal(string $slug, string $lokal)
    {
        $inwestycja = $this->inwestycja($slug);
        $lokale = $inwestycja->lokaleNaFroncie();

        /** @var Property|null $property */
        $property = $lokale->firstWhere('id', (int) $lokal);
        abort_unless($property, 404);

        // Stary albo obcięty adres (np. zmieniony numer lokalu) -> właściwy
        if (rtrim(request()->url(), '/') !== $property->link()) {
            return redirect($property->link(), 301);
        }

        $pozycja = $lokale->search(fn ($l) => $l->id === $property->id);

        return view('front.inwestycje.lokal', [
            'page' => Page::where('uri', 'inwestycje')->first(),
            'inwestycja' => $inwestycja,
            'lokal' => $property->load('priceHistory', 'priceComponents'),
            'poprzedni' => $lokale->get($pozycja - 1),
            'nastepny' => $lokale->get($pozycja + 1),
        ]);
    }

    /** Inwestycja z podstroną u nas - ukryta, bez lokali albo z adresem zewnętrznym: 404 */
    private function inwestycja(string $slug): Investment
    {
        $inwestycja = Investment::naFroncie()->with('city')->where('slug', $slug)->firstOrFail();
        abort_unless($inwestycja->maPodstrone(), 404);

        return $inwestycja;
    }
}
