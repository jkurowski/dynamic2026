<?php

namespace App\Observers;

use Illuminate\Support\Facades\File;

// CMS
use App\Models\File as UploadFile;

class FileObserver
{
    public function deleted(UploadFile $model)
    {
        $file = public_path('uploads/storage/' . $model->file);
        if (File::isFile($file)) {
            File::delete($file);
        }
    }
}
