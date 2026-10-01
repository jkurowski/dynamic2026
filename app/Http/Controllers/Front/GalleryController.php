<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

// CMS
use App\Models\Gallery;
use App\Models\Page;

class GalleryController extends Controller
{

    public function index()
    {
        $page = Page::where('id', 2)->first();
        $galleries = Gallery::where('status', 1)->orderBy('sort', 'asc')->get();

        return view('front.gallery.index', [
            'page' => $page,
            'galleries' => $galleries
        ]);
    }

    public function show($slug)
    {
        $page = Page::where('id', 2)->first();

        $gallery = Gallery::where('slug', $slug)->with('photos')->first();

        return view('front.gallery.show', [
            'page' => $page,
            'gallery' => $gallery
        ]);
    }
}
