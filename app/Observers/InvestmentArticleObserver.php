<?php

namespace App\Observers;

// CMS
use App\Models\InvestmentArticles;
use Illuminate\Support\Str;

class InvestmentArticleObserver
{

    /**
     * Handle the investment "saving" event.
     *
     * @param InvestmentArticles $page
     * @return void
     */
    public function saving(InvestmentArticles $page)
    {
        $page->slug = Str::slug($page->title);
    }
}
