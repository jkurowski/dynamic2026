<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class CityService
{
    /** Najdłuższy bok kodu QR - na stronie 151 px, zapas na ekrany o dużej gęstości */
    private const QR_ROZMIAR = 400;

    /**
     * Kod QR biura: PNG (bezstratnie - kody QR źle znoszą kompresję JPG) + WebP bezstratny.
     */
    public function uploadQr(string $title, UploadedFile $file, City $model, bool $delete = false): void
    {
        if ($delete) {
            $this->usunQr($model);
        }

        $base = date('His') . '_qr-' . Str::slug($title);
        File::ensureDirectoryExists(public_path(City::KATALOG_QR . 'webp'));

        $image = Image::make($file->getRealPath())->orientate()->resize(self::QR_ROZMIAR, self::QR_ROZMIAR, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        });

        $image->save(public_path(City::KATALOG_QR . $base . '.png'), 100, 'png');
        $image->save(public_path(City::KATALOG_QR . 'webp/' . $base . '.webp'), 100, 'webp');

        $model->update(['file' => $base . '.png']);
    }

    public function usunQr(City $model): void
    {
        if (!$model->file) {
            return;
        }

        foreach ([City::KATALOG_QR . $model->file, City::KATALOG_QR . 'webp/' . pathinfo($model->file, PATHINFO_FILENAME) . '.webp'] as $plik) {
            if (File::isFile(public_path($plik))) {
                File::delete(public_path($plik));
            }
        }
    }
}
