<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Models\Map;
use App\Models\Page;

class MapController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'mapa')->firstOrFail(['title','meta_title', 'meta_description']);
        return view('front.map.index', ['list' => Map::orderBy('id', 'desc')->get(), 'page' => $page]);
    }
}
