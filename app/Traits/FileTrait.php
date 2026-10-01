<?php
namespace App\Traits;

trait FileTrait {


    public function getFilePublicPath($filename)
    {
        return url('uploads/storage/' . $filename);
    }
}