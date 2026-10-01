<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Page;

class IndexController extends Controller
{
    private int $pageId;
    public function __construct()
    {
        $this->pageId = 8;
    }

    public function index()
    {
        $page = Page::find($this->pageId);
        return view('front.developro.investment.index', [
            'page' => $page
        ]);
    }
}
