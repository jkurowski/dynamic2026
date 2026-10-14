<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

//CMS
use App\Models\Plan;

class InvestmentService
{
    /**
     * Zdjęcie karty inwestycji (lista /inwestycje, strona główna, wyszukiwarka): kadr z config('images.investment')
     * - proporcje karty z szablonu (843x464) w 2x - jako JPG + WebP (investment/thumbs/webp/).
     */
    public function uploadThumb(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/thumbs/' . $model->file_thumb))) {
                File::delete(public_path('investment/thumbs/' . $model->file_thumb));
            }
            $staryWebp = public_path('investment/thumbs/webp/' . pathinfo((string) $model->file_thumb, PATHINFO_FILENAME) . '.webp');
            if ($model->file_thumb && File::isFile($staryWebp)) {
                File::delete($staryWebp);
            }
        }

        $base = date('His').'_'.Str::slug($title);
        $name = $base . '.jpg';
        File::ensureDirectoryExists(public_path('investment/thumbs/webp'));

        // orientate() - zdjęcia z telefonu mają obrót zapisany w EXIF
        $image = Image::make($file->getRealPath())->orientate()->fit(
            config('images.investment.thumb_width'),
            config('images.investment.thumb_height')
        );
        $image->save(public_path('investment/thumbs/' . $name), 85, 'jpg');
        $image->save(public_path('investment/thumbs/webp/' . $base . '.webp'), 80, 'webp');

        $model->update(['file_thumb' => $name]);
    }

    public function uploadLogo(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/logo/' . $model->file_logo))) {
                File::delete(public_path('investment/logo/' . $model->file_logo));
            }
        }

        $name = date('His').'_'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $file->storeAs('logo', $name, 'investment_uploads');

        $filepath = public_path('investment/logo/' . $name);
        Image::make($filepath)
            ->fit(
                config('images.investment.logo_width'),
                config('images.investment.logo_height')
            )
            ->save($filepath);

        $model->update(['file_logo' => $name]);
    }

    public function uploadPlan(object $model, UploadedFile $file)
    {

        if ($model->plan()->exists()) {
            if (File::isFile(public_path('investment/plan/' . $model->plan()->first()->file))) {
                File::delete(public_path('investment/plan/' . $model->plan()->first()->file));
            }
        }

        $name = date('His') . '_' . Str::slug($model->name) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('plan', $name, 'investment_uploads');

        $filepath = public_path('investment/plan/' . $name);
        Image::make($filepath)->resize(
            config('images.plan.width'),
            config('images.plan.height'),
            function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($filepath);

        Plan::updateOrCreate(
            ['investment_id' => $model->id],
            ['file' => $name]
        );
    }

    /**
     * Zdjęcie w nagłówku podstrony inwestycji (inwestycja.html: kadr 1019x650, zapis w 2x) - JPG + WebP.
     */
    public function uploadHeader(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/header/' . $model->file_header))) {
                File::delete(public_path('investment/header/' . $model->file_header));
            }
            $staryWebp = public_path('investment/header/webp/' . pathinfo((string) $model->file_header, PATHINFO_FILENAME) . '.webp');
            if ($model->file_header && File::isFile($staryWebp)) {
                File::delete($staryWebp);
            }
        }

        $base = date('His').'_'.Str::slug($title);
        $name = $base . '.jpg';
        File::ensureDirectoryExists(public_path('investment/header/webp'));

        $image = Image::make($file->getRealPath())->orientate()->fit(
            config('images.investment.header_width'),
            config('images.investment.header_height')
        );
        $image->save(public_path('investment/header/' . $name), 85, 'jpg');
        $image->save(public_path('investment/header/webp/' . $base . '.webp'), 80, 'webp');

        $model->update(['file_header' => $name]);
    }

    public function uploadBrochure(string $title, UploadedFile $file, object $model, bool $delete = false)
    {
        if ($delete && !empty($model->file_brochure)) {
            $brochurePath = public_path('investment/brochure/' . $model->file_brochure);

            if (File::exists($brochurePath) && File::isFile($brochurePath)) {
                File::delete($brochurePath);
            }
        }

        $name = date('His') . '_' . Str::slug($title) . '.' . $file->getClientOriginalExtension();

        // Save file to public/investment/brochure
        $file->move(public_path('investment/brochure'), $name);

        // Update model
        $model->update(['file_brochure' => $name]);
    }
}
