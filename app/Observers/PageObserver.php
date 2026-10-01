<?php

namespace App\Observers;

use Illuminate\Support\Str;

// CMS
use App\Models\Page;

class PageObserver
{

    /**
     * Handle the article "saving" event.
     *
     * @param Page $page
     * @return void
     */
    public function saving(Page $page)
    {
        $page->slug = Str::slug($page->title);

        if ($page->parent_id) {
            $array = Page::ancestorsOf($page->id)->pluck('slug')->toArray();
            array_push($array, $page->slug);
            $page->uri = implode('/', $array);
        } else {
            $page->uri = $page->slug;
        }
    }
}
