<?php

namespace App\Observers;

use Illuminate\Support\Str;

// CMS
use App\Models\Url;

class UrlObserver
{

    /**
     * Handle the article "saving" event.
     *
     * @param Url $url
     * @return void
     */
    public function saving(Url $url)
    {
        $url->slug = Str::slug($url->title);
        $url->type = 2;

        if ($url->parent_id) {
            $array = Url::ancestorsOf($url->id)->pluck('slug')->toArray();
            array_push($array, $url->slug);
            $url->uri = implode('/', $array);
        } else {
            $url->uri = $url->slug;
        }
    }
}
