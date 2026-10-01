<?php

namespace App\Http\Controllers\Front\Developro;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;

// CMS
use App\Models\Page;
use App\Repositories\InvestmentRepository;

class InvestmentController extends Controller
{
    private InvestmentRepository $repository;
    private int $pageId;

    public function __construct(InvestmentRepository $repository)
    {
        $this->repository = $repository;
        $this->pageId = 8;
    }

    public function show($lang, $slug)
    {
        $investment = Investment::with('sections')
            ->withMin('pricesProperties as min_price', 'price_search')
            ->withMax('pricesProperties as max_price', 'price_search')
            ->where('slug', $slug)
            ->firstOrFail();
        $page = Page::find($this->pageId);

        if($investment->status == 1){
            $images = $investment->images()->get();
            return view('front.investments.'.$slug.'.index', [
                'investment' => $investment,
                'page' => $page,
                'images' => $images
            ]);
        } else if($investment->status == 2){
            return view('front.developro.completed.show', [
                'investment' => $investment,
                'page' => $page
            ]);
        } else {
            return view('front.investments.'.$slug.'.index', [
                'investment' => $investment,
                'page' => $page
            ]);
        }
    }
}
