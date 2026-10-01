<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

//CMS
use App\Models\Settings;
use Spatie\Valuestore\Valuestore;

class SeoService
{
    public function uploadFavIcon(UploadedFile $file)
    {
        $favIcon = app(Settings::class)->get('page_favicon');

        if (File::isFile(public_path($favIcon))) {
            File::delete(public_path($favIcon));
        }

        $name = 'favicon.' . $file->getClientOriginalExtension();
        $file->storeAs('/', $name, 'public_uploads');
        $filepath = public_path('uploads/' . $name);
        Image::make($filepath)->fit(200, 200)->save($filepath);

        $settings = Valuestore::make(storage_path('app/settings.json'));
        $values = $settings->all();

        $values['page_favicon'] = $name;

        $settings->put($values);
    }
}
