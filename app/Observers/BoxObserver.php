<?php

namespace App\Observers;

use Illuminate\Support\Facades\File;

// CMS
use App\Models\Boxes;

class BoxObserver
{
    public function deleted(Boxes $boxes)
    {
        $file = public_path('uploads/boxes/' . $boxes->file);

        if (File::isFile($file)) {
            File::delete($file);
        }
    }
}
