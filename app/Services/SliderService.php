<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

//CMS
use App\Models\Slider;

class SliderService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false)
    {
        if ($delete) {
            $name = pathinfo($model->file, PATHINFO_FILENAME);

            File::delete(public_path('uploads/slider/' . $model->file));
            File::delete(public_path('uploads/slider/thumbs/' . $model->file));

            File::delete(public_path('uploads/slider/webp/' . $name . '.webp'));
            File::delete(public_path('uploads/slider/mobile/' . $name . '.webp'));
        }

        $slug = Str::slug($title);
        $name = date('His') . '_' . $slug . '.' . $file->getClientOriginalExtension();

        $file->storeAs('slider', $name, 'public_uploads');

        $filepath = public_path('uploads/slider/' . $name);

        // 🔥 katalogi
        File::ensureDirectoryExists(public_path('uploads/slider/webp'));
        File::ensureDirectoryExists(public_path('uploads/slider/mobile'));

        // 🔥 DESKTOP (1400px)
        $image = Image::make($filepath)
            ->resize(1400, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        $image->save($filepath, 80); // fallback jpg

        $image->encode('webp', 60)
            ->save(public_path('uploads/slider/webp/' . pathinfo($name, PATHINFO_FILENAME) . '.webp'));

        // 🔥 MOBILE (768px)
        $mobile = Image::make($filepath)
            ->resize(768, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        $mobile->encode('webp', 55)
            ->save(public_path('uploads/slider/mobile/' . pathinfo($name, PATHINFO_FILENAME) . '.webp'));

        // 🔥 THUMB (opcjonalnie)
        $thumb = Image::make($filepath)
            ->fit(400, 250);

        $thumb->save(public_path('uploads/slider/thumbs/' . $name), 80);

        $model->update(['file' => $name]);
    }
}
