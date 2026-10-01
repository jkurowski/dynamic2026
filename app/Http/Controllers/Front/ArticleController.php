<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Page;

/**
 * Aktualności na froncie (szablon dynamic-front/aktualnosci.html).
 * Ustalenie z klientem (15.07): na start brak pozycji w menu głównym - wejście ze strony głównej i ze stopki.
 */
class ArticleController extends Controller
{
    /** Kart na stronę - jak w projekcie (3 rzędy po 2) */
    private const NA_STRONE = 6;

    public function index()
    {
        $articles = Article::opublikowane()
            ->paginate(self::NA_STRONE, ['*'], 'strona')
            ->onEachSide(1);

        return view('front.article.index', [
            'page' => Page::where('uri', 'aktualnosci')->first(),
            'articles' => $articles,
        ]);
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->where('status', 1)->firstOrFail();

        $pozostale = Article::opublikowane()
            ->where('id', '!=', $article->id)
            ->take(3)
            ->get();

        return view('front.article.show', [
            'page' => Page::where('uri', 'aktualnosci')->first(),
            'article' => $article,
            'pozostale' => $pozostale,
        ]);
    }
}
