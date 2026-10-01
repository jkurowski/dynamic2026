<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class InvestmentArticleService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/articles/' . $model->file))) {
                File::delete(public_path('investment/articles/' . $model->file));
            }
            if (File::isFile(public_path('investment/articles/thumbs/' . $model->file))) {
                File::delete(public_path('investment/articles/thumbs/' . $model->file));
            }

            // WebP
            if (File::isFile(public_path('investment/articles/webp/' . $model->file_webp))) {
                File::delete(public_path('investment/articles/webp/' . $model->file_webp));
            }
            if (File::isFile(public_path('investment/articles/thumbs/webp/' . $model->file_webp))) {
                File::delete(public_path('investment/articles/thumbs/webp/' . $model->file_webp));
            }
        }

        $name = date('His').'_'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $name_webp = date('His').'_'.Str::slug($title).'.webp';

        $file->storeAs('articles', $name, 'investment_uploads');

        $file_path = public_path('investment/articles/' . $name);
        $file_thumb_path = public_path('investment/articles/thumbs/' . $name);

        Image::make($file_path)->fit(config('images.investment_article.big_width'), config('images.investment_article.big_height'))->save($file_path);
        Image::make($file_path)->fit(config('images.investment_article.thumb_width'), config('images.investment_article.thumb_height'))->save($file_thumb_path);

        // WebP
        $file_path_webp = public_path('investment/articles/webp/' . $name_webp);
        $file_thumb_path_webp = public_path('investment/articles/thumbs/webp/' . $name_webp);

        Image::make($file_path)->encode('webp', 75)->save($file_path_webp);
        Image::make($file_thumb_path)->encode('webp', 75)->save($file_thumb_path_webp);

        $model->update([
            'file' => $name,
            'file_webp' => $name_webp
        ]);
    }
}
