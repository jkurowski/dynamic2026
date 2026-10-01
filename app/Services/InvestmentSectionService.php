<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class InvestmentSectionService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/sections/' . $model->file))) {
                File::delete(public_path('investment/sections/' . $model->file));
            }
        }

        $name = date('His').'_'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $file->storeAs('sections', $name, 'investment_uploads');

        $file_path = public_path('investment/sections/' . $name);
        Image::make($file_path)->save($file_path);

        $model->update([
            'file' => $name
        ]);
    }
}
