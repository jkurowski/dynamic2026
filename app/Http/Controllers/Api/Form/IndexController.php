<?php

namespace App\Http\Controllers\Api\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WebFormRequest;
use App\Repositories\Client\ClientRepository;

//CMS

class IndexController extends Controller
{

    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(WebFormRequest $request)
    {
        $client = $this->repository->createClient($request);
        return response()->json($client, 201);
    }
}
