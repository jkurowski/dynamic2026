<?php namespace App\Repositories\Client;

use App\Models\ClientNote;
use App\Repositories\BaseRepository;

class NoteRepository extends BaseRepository implements NoteRepositoryInterface
{
    protected $model;

    public function __construct(ClientNote $model)
    {
        parent::__construct($model);
    }

    public function getNotes($attributes, $client): object
    {
        return $this->model->when($client->id, function($query) use ($client) {
                $query->where('client_id', $client->id);
            })->when($attributes['user_id'], function($query) use ($attributes) {
                $query->where('user_id', $attributes['user_id']);
            })
            ->when($user_id = auth()->id(), function($query) use ($user_id) {
                $query->where("user_id", $user_id);
            })
            ->orderBy('id', 'DESC')
            ->with('user')
            ->get();
    }

    public function getNote($client, $note): object
    {
        return $this->model->where('id', '=', $note->id)->where('client_id', '=', $client->id)->get();
    }

    public function createNote(array $attributes, $client): array
    {

        $note = new ClientNote();
        $note->client_id = $client->id;
        $note->user_id = auth()->id();
        $note->text = $attributes['text'];
        $note->save();

        return [
            'success' => true,
            'note' => [
                'id' => $note->id,
                'text' => $note->text,
                'user' => $note->user()->first()->toArray(),
                'created_at' => $note->created_at->diffForHumans()
            ]
        ];
    }

    public function destroyNote($note, $client)
    {
        $deleted = ClientNote::where('id', '=', $note->id)->where('client_id', '=', $client->id)->delete();
        if($deleted){
            return ['success' => true];
        } else {
            return [
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    "client_notes" => "Wybrana notatka nie istnieje"
                ]
            ];
        }
    }

    public function updateNote($request, $client, $note): array
    {
        $note->update(['text' => $request->input('text')]);
        return [
            'success' => true,
            'note' => [
                'text' => $request->input('text')
            ]
        ];
    }

    public function pinNote($request, $client, $note): array
    {
        $note->update(['pinned' => ($note->pinned == 0) ? 1 : 0]);
        return [
            'success' => true,
            'note' => [
                'id' => $note->id,
                'pinned' => $note->pinned
            ]
        ];
    }
}
