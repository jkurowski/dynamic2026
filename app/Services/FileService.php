<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

//CMS
use App\Models\Boxes;

class FileService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('uploads/storage/' . $model->file))) {
                File::delete(public_path('uploads/storage/' . $model->file));
            }
        }

        $name = date('His').'_'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $file->storeAs('storage', $name, 'public_uploads');

        $model->update([
            'file' => $name,
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'mime' => $file->getClientMimeType()
        ]);
    }
}
