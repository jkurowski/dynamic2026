<?php namespace App\Repositories\Chat;

use App\Repositories\BaseRepository;

//CMS
use App\Models\ClientMessage;
use App\Models\Client;

class ChatRepository extends BaseRepository implements ChatRepositoryInterface
{
    protected $model;

    public function __construct(ClientMessage $model)
    {
        parent::__construct($model);
    }

    public function getChat(Client $client)
    {
        return $this->model::whereClientId($client->id)->latest()->get();
    }

    public function storeAnswer($attributes, Client $client)
    {

        $this->model->create([
            'parent_id' => $attributes->input('id'),
            'user_id' => auth()->id(),
            'client_id' => $client->id,
            'message' => $attributes->input('message'),
            'source' => 'System',
            'ip' => $attributes->ip(),
            'created_at' => now()
        ]);
    }

    public function markMessage($attributes, Client $client)
    {
        $message = $this->model::whereClientId($client->id)->whereId($attributes->input('id'))->first();

        if ($message->mark_at == null) {
            $message->mark_at = now();
        } else {
            $message->mark_at = null;
        }

        return $message->save();
    }
}
