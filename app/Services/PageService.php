<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class PageService
{
    public function uploadHeader(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('uploads/headers/' . $model->file_header))) {
                File::delete(public_path('uploads/headers/' . $model->file_header));
            }
        }

        $name = date('His').'_'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $file->storeAs('headers', $name, 'public_uploads');

        $filepath = public_path('uploads/headers/' . $name);
        Image::make($filepath)
            ->fit(
                config('images.investment.header_width'),
                config('images.investment.header_height')
            )
            ->save($filepath);

        $model->update(['file_header' => $name]);
    }
}
