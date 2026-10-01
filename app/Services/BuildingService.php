<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class BuildingService
{
    public function uploadPlan(string $title, UploadedFile $file, object $model, int $investment_id, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/building/' . $model->file))) {
                File::delete(public_path('investment/building/' . $model->file));
            }
            if (File::isFile(public_path('investment/building/webp/' . $model->file_webp))) {
                File::delete(public_path('investment/building/webp/' . $model->file_webp));
            }
        }

        $name = date('His') . '_' . Str::slug($title) . '.' . $file->getClientOriginalExtension();
        $name_webp = date('His') . '_' . Str::slug($title) . '.webp';
        $file->storeAs('building', $name, 'investment_uploads');

        $filepath = public_path('investment/building/' . $name);
        $file_list_path_webp = public_path('investment/building/webp/' . $name_webp);

        Image::make($filepath)
            ->resize(
                config('images.building_plan.width'),
                config('images.building_plan.height'),
                function ($constraint) {
                    $constraint->aspectRatio();
                }
            )->save($filepath);

        Image::make($filepath)->encode('webp', 90)->save($file_list_path_webp);

        $model->update([
            'investment_id' => $investment_id,
            'file' => $name,
            'file_webp' => $name_webp
        ]);
    }
}
