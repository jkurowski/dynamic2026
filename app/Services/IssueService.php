<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

//CMS
use App\Models\Issue;
use App\Models\IssueFile;


class IssueService
{
    /**
     * @throws Exception
     */
    public function upload(Issue $issue, UploadedFile $file): array
    {

        $uploaded_file = $file->getClientOriginalName();
        $uploaded_file_name = pathinfo($uploaded_file,PATHINFO_FILENAME);
        $destination_file_name = date('His').'_'.Str::slug($uploaded_file_name).'.' . $file->getClientOriginalExtension();

        $file->storeAs('issue_files', $destination_file_name, 'public_uploads');

        $issue_file = IssueFile::create([
            'user_id' => auth()->id(),
            'issue_id' => $issue->id,
            'name' => $uploaded_file,
            'file' => $destination_file_name,
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'mime' => $file->getClientMimeType()
        ]);

        if ($issue_file->exists) {
            return [
                'id' => $issue_file->id,
                'user' => $issue_file->user()->first()->toArray(),
                'name' => $uploaded_file,
                'file' => $destination_file_name,
                'created_at' => $issue_file->created_at->diffForHumans(),
                'size' => parseFilesize($file->getSize()),
                'icon' => mime2icon($file->getClientMimeType())
            ];
        } else {
            throw new Exception('Error in saving data.');
        }

    }
}
