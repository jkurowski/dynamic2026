<?php

namespace App\Http\Controllers\Front\Developro\Page;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Property;
use App\Models\RodoRules;
use App\Models\RodoSettings;
use App\Repositories\InvestmentRepository;

class IndexController extends Controller
{
    private InvestmentRepository $repository;
    private int $pageId;

    public function __construct(InvestmentRepository $repository)
    {
        $this->repository = $repository;
        $this->pageId = 11;
    }

    public function index($slug, $page)
    {
        $investment = $this->repository->findBySlug($slug);
        $investmentPage = $investment->pages()->where('slug', $page)->first();
        $menu_page = Page::where('id', $this->pageId)->first();

        return view('front.developro.investment_page.index', [
            'investment' => $investment,
            'page' => $menu_page,
            'investment_page' => $investmentPage,
            'obligation' => RodoSettings::find(1),
            'rules' => RodoRules::orderBy('sort')->whereActive(1)->get()
        ]);
    }
}
