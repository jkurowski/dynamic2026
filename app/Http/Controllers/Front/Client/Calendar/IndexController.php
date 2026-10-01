<?php

namespace App\Http\Controllers\Front\Client\Calendar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EventsRequest;
use App\Models\Client;
use App\Repositories\Calendar\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    private $repository;
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $client = Auth::guard('client')->user();
        return view('front.auth.client.calendar.index', ['client' => $client]);
    }

    public function show(EventsRequest $request)
    {
        $client = Auth::guard('client')->user();
        return $this->repository->getClientEventsWithoutUser($request, $client);
    }
}
