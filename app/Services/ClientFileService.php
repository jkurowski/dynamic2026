<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

//CMS
use App\Models\Client;
use App\Models\ClientFile;


class ClientFileService
{
    /**
     * @throws Exception
     */
    public function upload(Client $client, UploadedFile $file): array
    {

        $uploaded_file = $file->getClientOriginalName();
        $uploaded_file_name = pathinfo($uploaded_file,PATHINFO_FILENAME);
        $destination_file_name = date('His').'_'.Str::slug($uploaded_file_name).'.' . $file->getClientOriginalExtension();

        $file->storeAs('user_files', $destination_file_name, 'public_uploads');

        $client_file = ClientFile::create([
            'user_id' => auth()->id(),
            'client_id' => $client->id,
            'name' => $uploaded_file,
            'file' => $destination_file_name,
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'mime' => $file->getClientMimeType()
        ]);

        if ($client_file->exists) {
            return [
                'id' => $client_file->id,
                'user' => $client_file->user()->first()->toArray(),
                'name' => $uploaded_file,
                'file' => $destination_file_name,
                'created_at' => $client_file->created_at->diffForHumans(),
                'size' => parseFilesize($file->getSize()),
                'icon' => mime2icon($file->getClientMimeType())
            ];
        } else {
            throw new Exception('Error in saving data.');
        }

    }

    /**
     * @throws Exception
     */
    public function addFile(Client $client, $file): array
    {

        $storage = Storage::disk('public_uploads');
        $file_path = '/user_files/' . $file;
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);

        $size = $storage->size($file_path);
        $mime = $storage->mimeType($file_path);

        $client_file = ClientFile::create([
            'user_id' => auth()->id(),
            'client_id' => $client->id,
            'name' => $file,
            'file' => $file,
            'size' => $size,
            'extension' => $ext,
            'mime' => $mime,
            'type' => 1
        ]);

        if ($client_file->exists) {
            return [
                'id' => $client_file->id,
                'user' => $client_file->user()->first()->toArray(),
                'name' => $file,
                'file' => $file,
                'created_at' => $client_file->created_at->diffForHumans(),
                'size' => parseFilesize($size),
                'icon' => mime2icon($mime)
            ];
        } else {
            throw new Exception('Error in saving data.');
        }

    }
}
