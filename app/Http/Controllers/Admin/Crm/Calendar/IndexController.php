<?php

namespace App\Http\Controllers\Admin\Crm\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

//Api
use App\Http\Requests\Api\EventsRequest;
use App\Http\Requests\Api\MoveEventRequest;
use App\Http\Requests\Api\PostEventRequest;

//CMS
use App\Repositories\Calendar\EventRepository;
use App\Models\Department;
use App\Models\Investment;
use App\Models\Event;

class IndexController extends Controller
{
    private $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.crm.calendar.index');
    }

    public function show(EventsRequest $request)
    {
        return $this->repository->getEvents($request, Auth::id());
    }

    public function table()
    {
        return view('admin.crm.calendar.table');
    }

    public function datatable(Request $request)
    {
        return $this->repository->getEventsAsTable($request, Auth::id());
    }

    public function create(Request $request)
    {
        if (request()->ajax()) {
            $date = Carbon::parse($request->get('date'));
            return view('admin.crm.modal.calendar-event', [
                'date' => $date->format('Y-m-d'),
                'time' =>  $date->format('H:i'),
                'allday' => $request->allDay,
                'investments' => Investment::select(['id', 'name'])->get(),
                'departments' => Department::all()->pluck('name', 'id')
            ])->render();
        }
    }

    public function store(PostEventRequest $request)
    {
        if (request()->ajax()) {
            $this->repository->create($request->validated());
            return response()->json(['success' => true]);
        }
    }

    public function edit(Event $event)
    {
        if (request()->ajax()) {
            return view('admin.crm.modal.calendar-event-edit', [
                'event' => $event->load('client'),
                'investments' => Investment::select(['id', 'name'])->get(),
                'departments' => Department::all()->pluck('name', 'id')
            ])->render();
        }
    }

    public function update(PostEventRequest $request, Event $event)
    {
        if (request()->ajax()) {
            $this->repository->update($request->validated(), $event);
            return response()->json(['success' => true]);
        }
    }

    public function move(MoveEventRequest $request, Event $event)
    {
        if (request()->ajax()) {
            $event = $this->repository->moveEvent($request, $event);
            return response()->json($event);
        }
    }

    public function destroy(Event $event)
    {
        if (request()->ajax()) {
            $event->delete();
            return response()->json(['success' => true], 201);
        }
    }

    public function changeEventStatus(Event $event)
    {
        $status = $this->repository->changeEventStatus($event);
        return response()->json(['success' => true, 'status' => $status]);
    }
}
