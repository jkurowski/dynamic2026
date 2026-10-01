<?php

namespace App\Observers;

use Illuminate\Support\Facades\File;

// CMS
use App\Models\IssueFile;

class IssueFileObserver
{
    public function deleted(IssueFile $model)
    {
        if($model->type == 0) {
            $file = public_path('uploads/issue_files' . $model->file);
            if (File::isFile($file)) {
                File::delete($file);
            }
        }
    }
}
