<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Inline;
use App\Models\Page;

class MenuController extends Controller
{
    public function index($uri = null)
    {
        $page = Page::where('uri', $uri)->firstOrFail();
        //$parent = Page::ancestorsOf($page->id)->first();

        $data = [];

        $inline = Inline::whereSlug($uri)->get()->toArray();

        if (!view()->exists('front.menupage.'.$uri)) {
            abort(404);
        }

        return view('front.menupage.'.$uri)
            ->with([
                'page' => $page,
                //'parent' => $parent
                'uri' => $uri,
                'data' => $data,
                'array' => $inline,
            ]);
    }

    public function kredyty()
    {
        $uri = 'kredyty';
        $page = Page::where('uri', $uri)->firstOrFail();
        $inline = Inline::whereSlug($uri)->get()->toArray();

        if (!view()->exists('front.menupage.'.$uri)) {
            abort(404);
        }

        return view('front.menupage.'.$uri)
            ->with([
                'page' => $page,
                'uri' => $uri,
                'array' => $inline,
            ]);
    }

    public function wykonczeniowe()
    {
        $uri = 'programy-wykonczeniowe';
        $page = Page::where('uri', $uri)->firstOrFail();
        $inline = Inline::whereSlug($uri)->get()->toArray();

        if (!view()->exists('front.menupage.'.$uri)) {
            abort(404);
        }

        return view('front.menupage.'.$uri)
            ->with([
                'page' => $page,
                'uri' => $uri,
                'array' => $inline,
            ]);
    }
}
