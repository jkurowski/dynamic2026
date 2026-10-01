<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Spatie\Translatable\HasTranslations;

class Inline extends Model
{
    //use HasTranslations;

    const UPDATED_AT = null;
    const CREATED_AT = null;

    //public $translatable = ['modaltytul', 'modaleditor', 'modaleditortext', 'modallink', 'modallinkbutton', 'file_alt'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_place',
        'modaltytul',
        'modaleditor',
        'modaleditortext',
        'modallink',
        'modallinkbutton',
        'file',
        'file_alt',
        'file_width',
        'file_height',
        'sort '
    ];

    public static function getElements($id){
        return static::where('id_place', $id)->get();
    }

    public function upload($name, $uploadfile, $width, $height)
    {

        $oldFile = self::where('id', $this->id)->pluck('file')->first();

        if($oldFile) {
            $img = public_path('uploads/inline/' . $oldFile);
            if (file_exists($img)) {
                unlink($img);
            }
        }

        $filename = Str::slug($name) . '_' . Str::random(3) . '.' .$uploadfile->getClientOriginalExtension();
        $uploadfile->storeAs('inline', $filename, 'public_uploads');

//        $filepath = public_path('uploads/inline/' . $filename);
//        Image::make($filepath)->fit($width, $height)->save($filepath);

        $this->update(['file' => $filename ]);

        return $filename;
    }
}
