<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

// CMS
use App\Models\Article;
use App\Models\Inline;
use App\Models\Property;
use App\Models\Slider;

class IndexController extends Controller
{
    public function index()
    {
        // Slajdy hero z panelu (Slider); gdy brak aktywnych - slajdy z makiety
        $slajdy = Slider::slajdyHero();

        $promotion = Property::where('highlighted', '=', 1)->get();

        // Karuzela aktualności na stronie głównej (strzałki przy więcej niż 3 wpisach)
        $aktualnosci = Article::opublikowane()->take(9)->get();

        return view('front.homepage.index', [
            'array' => Inline::getElements(1),
            'slajdy' => $slajdy,
            'promotion' => $promotion,
            'aktualnosci' => $aktualnosci,
        ]);
    }

}
