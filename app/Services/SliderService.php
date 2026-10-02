<?php

namespace App\Services;

use App\Models\Slider;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Zdjęcie slajdu hero na stronie głównej. Kadr 5:3 w rozmiarach z config('images.slider.rozmiary')
 * (1920x1152, 1280x768, 768x461), każdy jako JPG + WebP, plus miniatura do listy w panelu.
 */
class SliderService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false)
    {
        if ($delete) {
            $model->usunPliki();
        }

        $base = date('His') . '_' . Str::slug($title);
        $name = $base . '.jpg';

        // orientate() - zdjęcia z telefonu mają obrót zapisany w EXIF
        $source = Image::make($file->getRealPath())->orientate();

        foreach (config('images.slider.rozmiary') as $width => $height) {
            File::ensureDirectoryExists(public_path(Slider::KATALOG . $width . '/webp'));

            $image = (clone $source)->fit($width, $height);
            $image->save(public_path(Slider::KATALOG . $width . '/' . $name), 85, 'jpg');
            $image->save(public_path(Slider::KATALOG . $width . '/webp/' . $base . '.webp'), 80, 'webp');
        }

        // Miniatura do listy slajdów w panelu
        File::ensureDirectoryExists(public_path(Slider::KATALOG . 'thumbs'));
        (clone $source)->fit(config('images.slider.thumb_width'), config('images.slider.thumb_height'))
            ->save(public_path(Slider::KATALOG . 'thumbs/' . $name), 80, 'jpg');

        $model->update(['file' => $name]);
    }
}
