<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class BuildingFloorService
{
    public function uploadPlan(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/floor/' . $model->file))) {
                File::delete(public_path('investment/floor/' . $model->file));
            }
            if (File::isFile(public_path('investment/floor/webp/' . $model->file_webp))) {
                File::delete(public_path('investment/floor/webp/' . $model->file_webp));
            }
        }

        $name = date('His') . '_' . Str::slug($title) . '.' . $file->getClientOriginalExtension();
        $name_webp = date('His') . '_' . Str::slug($title) . '.webp';
        $file->storeAs('floor', $name, 'investment_uploads');

        $filepath = public_path('investment/floor/' . $name);
        $file_list_path_webp = public_path('investment/floor/webp/' . $name_webp);

        Image::make($filepath)
            ->resize(
                config('images.floor.plan_width'),
                config('images.floor.plan_height'),
                function ($constraint) {
                    $constraint->aspectRatio();
                }
            )->save($filepath);

        Image::make($filepath)->encode('webp', 90)->save($file_list_path_webp);

        $model->update([
            'file' => $name,
            'file_webp' => $name_webp
        ]);
    }
}
