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
        $base = date('His') . '_' . $slug;

        // Plik "normalny" zawsze jako JPG (niezależnie od formatu wgranego) + kopia WebP, w dwóch rozmiarach:
        // big - strona wpisu, thumb - karty (lista aktualności i karuzela na stronie głównej).
        $name = $base . '.jpg';
        $name_webp = $base . '.webp';

        // orientate() - zdjęcia z telefonu mają obrót zapisany w EXIF, bez tego lądują "na boku"
        $source = Image::make($file->getRealPath())->orientate();

        $sizes = [
            ['uploads/articles/', config('images.article.big_width'), config('images.article.big_height')],
            ['uploads/articles/thumbs/', config('images.article.thumb_width'), config('images.article.thumb_height')],
        ];

        foreach ($sizes as [$dir, $width, $height]) {
            File::ensureDirectoryExists(public_path($dir . 'webp'));

            $image = (clone $source)->fit($width, $height);
            $image->save(public_path($dir . $name), 85, 'jpg');
            $image->save(public_path($dir . 'webp/' . $name_webp), 80, 'webp');
        }

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
