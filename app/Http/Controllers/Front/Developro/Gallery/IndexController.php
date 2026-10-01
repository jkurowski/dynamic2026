<?php

namespace App\Http\Controllers\Front\Developro\Gallery;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Repositories\InvestmentArticleRepository;
use App\Repositories\InvestmentRepository;

class IndexController extends Controller
{
    private InvestmentRepository $repository;
    private int $pageId;

    public function __construct(InvestmentRepository $repository)
    {
        $this->repository = $repository;
        $this->pageId = 8;
    }

    public function index($language, $slug)
    {
        $investment = $this->repository->findBySlug($slug);
        $investmentPage = $investment->pages()->where('slug', 'galeria')->first();
        $menu_page = Page::where('id', $this->pageId)->first();

        return view('front.developro.investment_gallery.index', [
            'investment' => $investment,
            'page' => $menu_page,
            'investment_page' => $investmentPage
        ]);
    }
}
