<?php

namespace App\Http\Controllers\Api\Note;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Requests\ClientNoteFormRequest;
use App\Models\Client;
use App\Models\ClientNote;
use App\Repositories\Client\NoteRepository;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private $repository;

    public function __construct(NoteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(UserRequest $request, Client $client): object
    {
        return $this->repository->getNotes($request, $client);
    }

    public function show(Client $client, ClientNote $note): object
    {
        return $this->repository->getNote($client, $note);
    }

    public function store(ClientNoteFormRequest $request, Client $client): array
    {
        return $this->repository->createNote($request->validated(), $client);
    }

    public function update(ClientNoteFormRequest $request, Client $client, ClientNote $note): array
    {
        return $this->repository->updateNote($request, $client, $note);
    }

    public function pinned(Request $request, Client $client, ClientNote $note): array
    {
        return $this->repository->pinNote($request, $client, $note);
    }

    public function destroy(Client $client, ClientNote $note): array
    {
        return $this->repository->destroyNote($note, $client);
    }
}
