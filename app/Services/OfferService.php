<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

//CMS
use App\Models\Offer;

class OfferService
{
    public function upload(Offer $offer, UploadedFile $file): array
    {

        $uploaded_file = $file->getClientOriginalName();
        $uploaded_file_name = pathinfo($uploaded_file,PATHINFO_FILENAME);
        $destination_file_name = date('His').'_'.Str::slug($uploaded_file_name).'.' . $file->getClientOriginalExtension();

        $file->storeAs('offer', $destination_file_name, 'public_uploads');

        $fileInfo = [
            'id' => md5($destination_file_name),
            'user' => [
                'name' => auth()->user()->name,
                'surname' => auth()->user()->surname,
            ],
            'name' => $uploaded_file,
            'file' => $destination_file_name,
            'created_at' => now()->diffForHumans(),
            'size' => parseFilesize($file->getSize()),
            'icon' => mime2icon($file->getClientMimeType())
        ];

        $attachments = json_decode($offer->attachments, true) ?? [];
        $attachments[] = $fileInfo;

        $offer->update(['attachments' => json_encode($attachments)]);

        return $fileInfo;
    }
}
