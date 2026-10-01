<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class PropertyService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/property/' . $model->file))) {
                File::delete(public_path('investment/property/' . $model->file));
            }
            if (File::isFile(public_path('investment/property/thumbs/' . $model->file))) {
                File::delete(public_path('investment/property/thumbs/' . $model->file));
            }
            if (File::isFile(public_path('investment/property/list/' . $model->file))) {
                File::delete(public_path('investment/property/list/' . $model->file));
            }

            // WebP
            if (File::isFile(public_path('investment/property/webp/' . $model->file_webp))) {
                File::delete(public_path('investment/property/webp/' . $model->file_webp));
            }
            if (File::isFile(public_path('investment/property/thumbs/webp/' . $model->file_webp))) {
                File::delete(public_path('investment/property/thumbs/webp/' . $model->file_webp));
            }
            if (File::isFile(public_path('investment/property/list/webp/' . $model->file_webp))) {
                File::delete(public_path('investment/property/list/webp/' . $model->file_webp));
            }
        }

        $name = date('His').'_'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $name_webp = date('His').'_'.Str::slug($title).'.webp';
        $file->storeAs('property', $name, 'investment_uploads');

        // Property card image
        $file_path = public_path('investment/property/' . $name);
        Image::make($file_path)->resize(config('images.property_plan.width'), config('images.property_plan.height'), function ($constraint) {
            $constraint->aspectRatio();
        })->save($file_path);

        // Property card thumb image
        $file_thumb_path = public_path('investment/property/thumbs/' . $name);
        Image::make($file_path)->resize(config('images.property_thumb.width'), config('images.property_thumb.height'), function ($constraint) {
            $constraint->aspectRatio();
        })->save($file_thumb_path);

        // Property list image
        $file_list_path = public_path('investment/property/list/' . $name);
        Image::make($file_path)->resize(config('images.property_list.width'), config('images.property_list.height'), function ($constraint) {
            $constraint->aspectRatio();
        })->save($file_list_path);

        // WebP
        $file_path_webp = public_path('investment/property/webp/' . $name_webp);
        $file_thumb_path_webp = public_path('investment/property/thumbs/webp/' . $name_webp);
        $file_list_path_webp = public_path('investment/property/list/webp/' . $name_webp);

        Image::make($file_path)->encode('webp', 90)->save($file_path_webp);
        Image::make($file_thumb_path)->encode('webp', 90)->save($file_thumb_path_webp);
        Image::make($file_list_path)->encode('webp', 90)->save($file_list_path_webp);

        // Update
        $model->update([
            'file' => $name,
            'file_webp' => $name_webp
        ]);
    }

    public function uploadPdf(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('investment/property/pdf/' . $model->file_pdf))) {
                File::delete(public_path('investment/property/pdf/' . $model->file_pdf));
            }
        }

        $name = date('His').'_'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $file->storeAs('property/pdf', $name, 'investment_uploads');
        $model->update(['file_pdf' => $name]);
    }
}
