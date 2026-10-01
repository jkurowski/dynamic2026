<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Investment;
use App\Models\Page;
use Illuminate\Http\Request;

class CompletedController extends Controller
{
    private int $pageId;
    public function __construct()
    {
        $this->pageId = 7;
    }

    public function index()
    {
        $page = Page::find($this->pageId);
        $investments = Investment::whereStatus(2)->get();

        return view('front.developro.completed.index', [
            'page' => $page,
            'investments' => $investments
        ]);
    }

    public function show($lang, $slug)
    {
        $page = Page::find($this->pageId);
        $city = City::whereSlug($slug)->first();

        $investments = Investment::whereCityId($city->id)->whereStatus(2)->get();

        return view('front.developro.completed.index', [
            'page' => $page,
            'investments' => $investments,
            'city' => $city
        ]);
    }
}
