<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

//CMS
use App\Models\Boxes;

class EmailGeneratorService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false)
    {

        if ($delete) {
            if (File::isFile(public_path('uploads/email/' . $model->file))) {
                File::delete(public_path('uploads/email/' . $model->file));
            }
        }

        $name = date('His').'_'.Str::slug($title).'.' . $file->getClientOriginalExtension();
        $file->storeAs('email', $name, 'public_uploads');
        $filepath = public_path('uploads/email/' . $name);

        Image::make($filepath)->fit(600, 600)->save($filepath);

        $model->update(['file' => $name, 'content' => '<div class="email-node" data-uuid="'.$title.'" data-type="image"><div style="padding-top:15px;padding-bottom:15px"><img src="'.asset('uploads/email/'.$name).'" alt="Pobierz obrazek" width="" height="" style="display:block; width:100%; height:auto;"></div></div>']);

        return asset('uploads/email/' . $name);
    }
}
