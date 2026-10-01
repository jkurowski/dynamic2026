<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EventsRequest;
use App\Http\Requests\Api\MoveEventRequest;
use App\Http\Requests\Api\PostEventRequest;
use App\Models\Client;
use App\Models\Event;
use App\Repositories\Calendar\EventRepository;

class IndexController extends Controller
{
    private $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(EventsRequest $request)
    {
        return $this->repository->getEvents($request);
    }

    public function show(EventsRequest $request, Client $client)
    {
        return $this->repository->getClientEvents($request, $client);
    }

    public function move(MoveEventRequest $request, Event $event)
    {
        $event = $this->repository->moveEvent($request, $event);
        return response()->json($event, 201);
    }

    public function store(PostEventRequest $request)
    {
        $this->repository->create($request->validated());
        return response()->json(['success' => true], 201);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['success' => true]);
    }

    public function changeEventStatus(Event $event)
    {
        $status = $this->repository->changeEventStatus($event);
        return response()->json(['success' => true, 'status' => $status]);
    }
}
