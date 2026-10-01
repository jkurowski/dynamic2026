<?php

namespace App\Observers;

use Illuminate\Support\Facades\File;

// CMS
use App\Models\Floor;

class FloorObserver
{
    /**
     * Handle the Floor "deleted" event.
     *
     * @param Floor $floor
     * @return void
     */
    public function deleted(Floor $floor)
    {
        if (File::isFile(public_path('investment/floor/' . $floor->file))) {
            File::delete(public_path('investment/floor/' . $floor->file));
        }
        if (File::isFile(public_path('investment/floor/webp/' . $floor->file_webp))) {
            File::delete(public_path('investment/floor/webp/' . $floor->file_webp));
        }
    }
}

