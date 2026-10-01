<?php namespace App\Repositories\Calendar;

use Carbon\Carbon;
use DateTime;
use Yajra\DataTables\DataTables;

//CMS
use App\Repositories\BaseRepository;

use App\Models\Event;

class EventRepository extends BaseRepository implements EventRepositoryInterface
{
    protected $model;

    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    public function getEvents($attributes, $user_id = null)
    {
        return Event::whereDate('start', '>=', date('Y-m-d', strtotime($attributes['start'])))
            ->whereDate('start', '<=', date('Y-m-d', strtotime($attributes['end'])))
            ->when($user_id, function($query) use ($user_id) {
                $query->where("user_id", $user_id);
            })
            ->get(['id', 'client_id', 'user_id', 'name', 'start', 'end', 'allday', 'time', 'type', 'note', 'active']);
    }
    public function getEventsAsTable($attributes, $user_id = null)
    {
        $start = $attributes['start'];
        $end = $attributes['end'];

        $list = Event::when($user_id, function($query) use ($user_id) {
                $query->where("user_id", $user_id);
            })
            ->when($start, function($query) use ($start) {
                $query->whereDate('start', '>=', date('Y-m-d', strtotime($start)));
            })
            ->when($end, function($query) use ($end) {
                $query->whereDate('start', '<=', date('Y-m-d', strtotime($end)));
            })
            ->with(['client', 'user'])
            ->orderByDesc('id')
            ->get(['id', 'client_id', 'user_id', 'name', 'start', 'allday', 'time', 'type', 'note', 'active']);

        return Datatables::of($list)
            ->editColumn('user_id', function ($row){
                if($row->user)
                {
                    return $row->user->name.' '.$row->user->surname;
                }
            })
            ->editColumn('client_id', function ($row){
                if($row->client)
                {
                    return $row->client->name;
                }
            })
            ->editColumn('type', function ($row){
                return eventType($row->type);
            })
            ->editColumn('start', function ($row){
                $date = Carbon::parse($row->start)->format('Y-m-d');
                $now = Carbon::now()->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();

                if($date >= $now){
                    return '<span>'.$date.'</span>';
                } else {
                    return '<span class="text-danger">'.$date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
                }
            })
            ->editColumn('active', function ($row){
                return eventTableStatus($row->active);
            })
            ->editColumn('time', function ($row){
                if(!$row->allday){
                    return Carbon::parse($row->start)->format('H:s');
                }
            })
            ->addColumn('actions', function ($row) {
                return view('admin.crm.calendar.actions', ['row' => $row]);
            })
            ->removeColumn('client')
            ->removeColumn('user')
            ->rawColumns(['type', 'start', 'active', 'actions'])
            ->make(true);
    }

    public function getClientEvents($attributes, $client = null)
    {
        return Event::whereDate('start', '>=', date('Y-m-d', strtotime($attributes['start'])))
            ->whereDate('start', '<=', date('Y-m-d', strtotime($attributes['end'])))
            ->when($client, function($query) use ($client) {
                $query->where("client_id", $client->id);
            })
            ->when($attributes['user_id'], function($query) use ($attributes) {
                $query->where("user_id", $attributes['user_id']);
            })
            ->when($user_id = auth()->id(), function($query) use ($user_id) {
                $query->where("user_id", $user_id);
            })
            ->get(['id', 'client_id', 'name', 'start', 'end', 'allday', 'time', 'type', 'note', 'active']);
    }

    public function getClientEventsWithoutUser($attributes, $client = null)
    {
        return Event::whereDate('start', '>=', date('Y-m-d', strtotime($attributes['start'])))
            ->whereDate('start', '<=', date('Y-m-d', strtotime($attributes['end'])))
            ->when($client, function($query) use ($client) {
                $query->where("client_id", $client->id);
            })
            ->get(['id', 'client_id', 'name', 'start', 'end', 'allday', 'time', 'type', 'note', 'active']);
    }

    public function moveEvent($attributes, $event): array
    {
        $createDate = new DateTime($attributes['date']);
        $allDay = $attributes['allday'] == "true" ? 1 : 0;
        $event->preventAttrSet = true;

        $event->update([
            'allday' => $allDay,
            'start' => $createDate->format('Y-m-d'),
            'time' => ($allDay) ? NULL : $createDate->format('H:i')
        ]);
        return ['success' => true, 'allday' => $allDay];
    }

    public function changeEventStatus($event)
    {
        $status = $event->active == 0 ? 1 : 0;
        $event->update([
            'active' => $status
        ]);
        return $status;
    }
}
