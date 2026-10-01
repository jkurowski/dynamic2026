<?php

namespace App\Observers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

// CMS
use App\Models\Article;

class ArticleObserver
{
    /**
     * Handle the article "deleted" event.
     *
     * @param Article $article
     * @return void
     */
    public function deleted(Article $article)
    {
        $file = public_path('uploads/articles/' . $article->file);
        $file_thumb = public_path('uploads/articles/thumbs/' . $article->file);

        if (File::isFile($file)) {
            File::delete($file);
        }
        if (File::isFile($file_thumb)) {
            File::delete($file_thumb);
        }

        // Webp files
        $file_webp = public_path('uploads/articles/webp/' . $article->file_webp);
        $file_webp_thumb = public_path('uploads/articles/thumbs/webp/' . $article->file_webp);

        if (File::isFile($file_webp)) {
            File::delete($file_webp);
        }
        if (File::isFile($file_webp_thumb)) {
            File::delete($file_webp_thumb);
        }

        // Facebook image
        $file_webp = public_path('uploads/articles/share/' . $article->file_facebook);
        if (File::isFile($file_webp)) {
            File::delete($file_webp);
        }
    }

    /**
     * Handle the article "saving" event.
     *
     * @param Article $article
     * @return void
     */
    public function saving(Article $article)
    {
        $article->slug = Str::slug($article->title);
    }
}
