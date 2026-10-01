<?php

namespace App\Observers;

use App\Models\Gallery;

class GalleryObserver
{
    /**
     * Handle the gallery "deleted" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function deleted(Gallery $gallery)
    {
        foreach ($gallery->photos as $photo) {
            if ($photo->file) {
                $image_path = public_path('uploads/gallery/images/' . $photo->file);
                $image_thumb_path = public_path('uploads/gallery/images/thumbs/' . $photo->file);
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                if (file_exists($image_thumb_path)) {
                    unlink($image_thumb_path);
                }
            }
        }
        $gallery->photos()->delete();
    }
}
