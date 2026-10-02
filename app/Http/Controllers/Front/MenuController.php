<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Inline;
use App\Models\Page;

class MenuController extends Controller
{
    /**
     * Strony z panelu (pages) pod adresem /{uri}.
     * Strona z własnym widokiem (front/menupage/{uri}.blade.php - Finansowanie, Poznaj nas...) dostaje go,
     * każda inna - ogólny widok strony tekstowej z treścią z panelu (front/menupage/strona-tekstowa).
     */
    public function index($uri = null)
    {
        $page = Page::where('uri', $uri)->where('active', 1)->firstOrFail();

        $data = [];

        $inline = Inline::whereSlug($uri)->get()->toArray();

        $view = view()->exists('front.menupage.' . $uri) ? 'front.menupage.' . $uri : 'front.menupage.strona-tekstowa';

        return view($view)
            ->with([
                'page' => $page,
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
