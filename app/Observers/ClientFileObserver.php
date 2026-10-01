<?php

namespace App\Observers;

use Illuminate\Support\Facades\File;

// CMS
use App\Models\ClientFile;

class ClientFileObserver
{
    public function deleted(ClientFile $model)
    {
        if($model->type == 0) {
            $file = public_path('uploads/storage/' . $model->file);
            if (File::isFile($file)) {
                File::delete($file);
            }
        }
    }
}
