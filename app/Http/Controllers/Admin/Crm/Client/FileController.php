<?php

namespace App\Http\Controllers\Admin\Crm\Client;

use App\Http\Controllers\Controller;
use App\Repositories\Client\FileRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

//CMS
use App\Repositories\Client\ClientRepository;
use App\Http\Requests\ClientFileFormRequest;
use App\Services\ClientFileService;

use App\Models\ClientFile;
use App\Models\Client;


class FileController extends Controller
{

    private $service;
    private $repository;
    private $fileRepository;

    public function __construct(ClientFileService $service, ClientRepository $repository, FileRepository $fileRepository)
    {
        $this->service = $service;
        $this->repository = $repository;
        $this->fileRepository = $fileRepository;
    }

    public function show(Client $client)
    {
        return view('admin.crm.client.file.index', [
            'client' => $client,
            'files' => $this->repository->getUserFiles($client)
        ]);
    }

    public function create(Request $request, Client $client)
    {
        if (request()->ajax() && $request->input('file')) {
            $upload = $this->service->addFile($client, $request->input('file'));

            return response()->json([
                'success' => true,
                'file' => $upload
            ]);
        }
    }

    public function store(Request $request, Client $client)
    {
        if (request()->ajax()) {

            if ($request->hasFile('qqfile')) {
                $upload = $this->service->upload($client, $request->file('qqfile'));
            }
            return response()->json([
                'success' => true,
                'file' => $upload
            ]);
        }
    }

    public function form(Request $request, Client $client, ClientFile $clientFile)
    {
        if (request()->ajax() && $clientFile->id) {
            $entry = $this->fileRepository->find($clientFile->id);
            return view('admin.crm.modal.user-file', ['entry' => $entry])->render();
        }
    }

    public function storeDesc(ClientFileFormRequest $request, ClientFile $clientFile)
    {
        if (request()->ajax() && $clientFile) {
            $entry = $this->fileRepository->find($clientFile->id);
            if($entry) {
                return $this->fileRepository->updateClientFileDesc($request->validated(), $entry);
            } else {
                throw new ModelNotFoundException("Entry not found");
            }
        }
    }

    public function destroyDesc(Request $request, ClientFile $clientFile)
    {
        if (request()->ajax() && $clientFile) {
            $entry = $this->fileRepository->find($clientFile->id);
            if($entry) {
                return $this->fileRepository->removeClientFileDesc($entry);
            } else {
                throw new ModelNotFoundException("Entry not found");
            }
        }
    }

    public function destroy(Client $client, ClientFile $clientFile)
    {
        if (request()->ajax()) {
            $clientFile->delete();
            return ['success' => true];
        }
    }
}
