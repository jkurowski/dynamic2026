<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

//CMS
use App\Models\Section;

class SectionService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('uploads/sections/' . $model->file))) {
                File::delete(public_path('uploads/sections/' . $model->file));
            }
        }

        $name = date('His').'_'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $file->storeAs('sections', $name, 'public_uploads');
        $filepath = public_path('uploads/sections/' . $name);

        Image::make($filepath)
            ->fit(
                config('images.section.width'),
                config('images.section.height')
            )->save($filepath);

        $model->update(['file' => $name]);
    }
}
