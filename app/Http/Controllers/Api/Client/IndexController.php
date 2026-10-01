<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Repositories\Client\ClientRepository;
use Illuminate\Http\Request;

//API

class IndexController extends Controller
{

    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->all();
    }

    public function show(Client $client)
    {
        return $client;
    }

    public function datatable()
    {
        return $this->repository->getDataTable();
    }

    public function rodo(Client $client, Request $request)
    {
        return $this->repository->getUserRodo($client, $request);
    }

    public function files(Client $client)
    {
        return $this->repository->getUserFiles($client);
    }

}
