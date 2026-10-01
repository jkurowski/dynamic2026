<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ContractService
{
    public function upload(string $title, UploadedFile $file, object $model, bool $delete = false)
    {
        if ($delete) {
            $this->deleteFileIfExists(public_path('uploads/contract/templates/' . $model->template));
        }

        $slug = Str::slug($title);
        $name = date('His') . '_' . $slug . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('uploads/contract/templates'), $name);
        $model->update([
            'template' => $name
        ]);
    }


    private function deleteFileIfExists($path): void
    {
        if (File::isFile($path)) {
            File::delete($path);
        }
    }
}
