<?php

namespace App\Observers;

use Illuminate\Support\Facades\File;

// CMS
use App\Models\Slider;

class SliderObserver
{
    /**
     * Handle the slider "deleted" event.
     *
     * @param Slider $slider
     * @return void
     */
    public function deleted(Slider $slider)
    {
        $file = public_path('uploads/slider/' . $slider->file);
        $file_thumb = public_path('uploads/slider/thumbs/' . $slider->file);

        if (File::isFile($file)) {
            File::delete($file);
        }
        if (File::isFile($file_thumb)) {
            File::delete($file_thumb);
        }
    }
}
