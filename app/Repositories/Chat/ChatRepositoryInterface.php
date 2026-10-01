<?php namespace App\Repositories\Chat;

use App\Models\Client;

interface ChatRepositoryInterface
{
    public function getChat(Client $client);
    public function storeAnswer($attributes, Client $client);
}
