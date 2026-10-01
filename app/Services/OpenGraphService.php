<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;

class OpenGraphService
{
    public function upload(UploadedFile $file)
    {
        $name = 'website_share.' . $file->getClientOriginalExtension();
        $file->storeAs('share', $name, 'public_uploads');
        $filepath = public_path('uploads/share/' . $name);
        Image::make($filepath)->fit(600, 314)->save($filepath);
    }
}
