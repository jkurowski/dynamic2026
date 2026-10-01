<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ArticleService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false)
    {
        if ($delete) {
            $this->deleteFileIfExists(public_path('uploads/articles/' . $model->file));
            $this->deleteFileIfExists(public_path('uploads/articles/thumbs/' . $model->file));
            $this->deleteFileIfExists(public_path('uploads/articles/webp/' . $model->file_webp));
            $this->deleteFileIfExists(public_path('uploads/articles/thumbs/webp/' . $model->file_webp));
        }

        $slug = Str::slug($title);
        $name = date('His') . '_' . $slug . '.' . $file->getClientOriginalExtension();

        $file_path = public_path('uploads/articles/' . $name);
        $file_thumb_path = public_path('uploads/articles/thumbs/' . $name);

        $image = Image::make($file->getRealPath());
        $image->fit(config('images.article.big_width'), config('images.article.big_height'))
            ->save($file_path)
            ->fit(config('images.article.thumb_width'), config('images.article.thumb_height'))
            ->save($file_thumb_path);

        // WebP
        $name_webp = date('His') . '_' . $slug . '.webp';

        $file_path_webp = public_path('uploads/articles/webp/' . $name_webp);
        $file_thumb_path_webp = public_path('uploads/articles/thumbs/webp/' . $name_webp);

        $image_webp = Image::make($file_path)->encode('webp', 75);
        $image_thumb_webp = Image::make($file_thumb_path)->encode('webp', 75);

        $image_webp->save($file_path_webp);
        $image_thumb_webp->save($file_thumb_path_webp);

        $model->update([
            'file' => $name,
            'file_webp' => $name_webp
        ]);
    }

    public function uploadFileFacebook(string $title, UploadedFile $file, object $model, bool $delete = false)
    {
        if ($delete && File::isFile(public_path('uploads/articles/share/' . $model->file_facebook))) {
            File::delete(public_path('uploads/articles/share/' . $model->file_facebook));
        }

        $name = date('His') . '_' . Str::slug($title) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('articles/share', $name, 'public_uploads');
        $filepath = public_path('uploads/articles/share/' . $name);

        $image = Image::make($filepath);
        $image->fit(600, 314)->save();

        $model->update(['file_facebook' => $name]);
    }

    private function deleteFileIfExists($path): void
    {
        if (File::isFile($path)) {
            File::delete($path);
        }
    }
}
