<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;

class HowToBuyController extends Controller
{

    public function index()
    {
        return view('front.howtobuy.index', [
            'page' => Page::where('id', 5)->first()
        ]);
    }

}
