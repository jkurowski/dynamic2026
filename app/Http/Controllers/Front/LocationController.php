<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

// CMS
use App\Repositories\MapRepository;
use App\Models\Page;

class LocationController extends Controller
{
    private $pageId;
    private $repository;

    public function __construct(MapRepository $repository)
    {
        $this->repository = $repository;
        $this->pageId = 4;
    }

    public function index()
    {
        $page = Page::where('id', $this->pageId)->first();

        return view('front.location.index', [
            'page' => $page,
            'markers' => $this->repository->all()
        ]);
    }
}
