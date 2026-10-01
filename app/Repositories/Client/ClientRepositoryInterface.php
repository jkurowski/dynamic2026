<?php namespace App\Repositories\Client;

interface ClientRepositoryInterface
{
    public function getDataTable();
    public function getUserRodo($client, array $attributes);
    public function getUserFiles($client);

    public function createClient($attributes);
}
