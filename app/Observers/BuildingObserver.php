<?php

namespace App\Observers;

use Illuminate\Support\Facades\File;

// CMS
use App\Models\Building;

class BuildingObserver
{
    /**
     * Handle the Building "deleted" event.
     *
     * @param Building $building
     * @return void
     */
    public function deleted(Building $building)
    {
        if (File::isFile(public_path('investment/building/' . $building->file))) {
            File::delete(public_path('investment/building/' . $building->file));
        }
        if (File::isFile(public_path('investment/building/webp/' . $building->file_webp))) {
            File::delete(public_path('investment/building/webp/' . $building->file_webp));
        }
    }
}
