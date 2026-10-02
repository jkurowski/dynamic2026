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
        // Wszystkie rozmiary JPG + WebP, miniatura i pliki starego formatu
        $slider->usunPliki();
    }
}
