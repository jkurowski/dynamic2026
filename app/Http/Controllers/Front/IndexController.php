<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

// CMS
use App\Models\Boxes;
use App\Models\Inline;
use App\Models\Property;
use App\Models\Slider;

class IndexController extends Controller
{
    public function index()
    {
        $slider = Slider::whereActive(1)->get();
        $boxes = Boxes::orderBy('sort')->get();

        $promotion = Property::where('highlighted', '=', 1)->get();

        return view('front.homepage.index', [
            'array' => Inline::getElements(1),
            'slider' => $slider,
            'boxes' => $boxes,
            'promotion' => $promotion
        ]);
    }

}
