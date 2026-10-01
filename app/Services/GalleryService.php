<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

//CMS
use App\Models\Gallery;

class GalleryService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false): void
    {
        if ($delete) {
            $this->deleteFileIfExists(public_path('uploads/gallery/' . $model->file));
            $this->deleteFileIfExists(public_path('uploads/gallery/webp/' . $model->file_webp));
        }

        $slug = Str::slug($title);
        $name = date('His') . '_' . $slug . '.' . $file->getClientOriginalExtension();

        $file->storeAs('gallery', $name, 'public_uploads');
        $filepath = public_path('uploads/gallery/' . $name);

        $image = Image::make($file->getRealPath());
        $image->resize(
                config('images.gallery.big_width'),
                config('images.gallery.big_height'),
                function ($constraint) {
                    $constraint->aspectRatio();
                }
                )->save($filepath);

        // WebP
        $name_webp = date('His') . '_' . $slug . '.webp';

        $file_path_webp = public_path('uploads/gallery/webp/' . $name_webp);

        $image_webp = Image::make($filepath)->encode('webp');
        $image_webp->save($file_path_webp);

        $model->update([
            'file' => $name,
            'file_webp' => $name_webp
        ]);
    }

    private function deleteFileIfExists($path): void
    {
        if (File::isFile($path)) {
            File::delete($path);
        }
    }
}
