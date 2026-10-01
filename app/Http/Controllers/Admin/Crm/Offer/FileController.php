<?php

namespace App\Http\Controllers\Admin\Crm\Offer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

//CMS
use App\Repositories\OfferRepository;
use App\Services\OfferService;
use App\Models\Offer;

class FileController extends Controller
{
    private OfferRepository $repository;
    private OfferService $service;

    public function __construct(OfferRepository $repository, OfferService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function upload(Request $request, Offer $issue)
    {
        if (request()->ajax()) {

            if ($request->hasFile('qqfile')) {
                $upload = $this->service->upload($issue, $request->file('qqfile'));
            }
            return response()->json([
                'success' => true,
                'file' => $upload
            ]);
        }
    }

    public function destroy(Offer $offer, $id)
    {
        if (request()->ajax()) {
            $attachmentId = $id;
            $attachments = json_decode($offer->attachments, true) ?? [];
            $attachmentIndex = array_search($attachmentId, array_column($attachments, 'id'));
            if ($attachmentIndex !== false) {
                $deletedAttachment = $attachments[$attachmentIndex];

                if (File::isFile(public_path('uploads/offer/' . $deletedAttachment['file']))) {
                    File::delete(public_path('uploads/offer/' . $deletedAttachment['file']));
                }


                unset($attachments[$attachmentIndex]);
                $attachments = array_values($attachments);
                $offer->update(['attachments' => json_encode($attachments)]);
                return ['success' => true];
            }
        }
    }
}