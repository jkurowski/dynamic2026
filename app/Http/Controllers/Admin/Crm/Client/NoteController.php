<?php

namespace App\Http\Controllers\Admin\Crm\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Requests\ClientNoteFormRequest;
use App\Models\Client;
use App\Models\ClientNote;
use App\Repositories\Client\NoteRepository;

//CMS

class NoteController extends Controller
{
    private $repository;

    public function __construct(NoteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function show(UserRequest $request, Client $client)
    {
        return view('admin.crm.client.note.index', [
            'client' => $client,
            'notes' => $this->repository->getNotes($request, $client)
        ]);
    }

    public function store(ClientNoteFormRequest $request, Client $client)
    {
        if (request()->ajax()) {
            return $this->repository->createNote($request->validated(), $client);
        }
    }

    public function update(ClientNoteFormRequest $request, Client $client, ClientNote $note)
    {
        if (request()->ajax()) {
            return $this->repository->updateNote($request, $client, $note);
        }
    }

    public function destroy(Client $client, ClientNote $note)
    {
        if (request()->ajax()) {
            return $this->repository->destroyNote($note, $client);
        }
    }
}
