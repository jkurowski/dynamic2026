<?php namespace App\Repositories;

use App\Models\Event;
use App\Models\Investment;
use App\Models\User;
use App\Notifications\SupervisorNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class InvestmentRepository extends BaseRepository
{
    protected $model;

    public function __construct(Investment $model)
    {
        parent::__construct($model);
    }

    public function getUniqueRooms(object $query)
    {
        return $query->sortBy('rooms')->unique('rooms')->pluck('rooms');
    }

    public function createPermissionName(int $id)
    {
        $permissionName = 'view-investment-' . $id;
        if (!Permission::where('name', $permissionName)->exists()) {
            Permission::create(['name' => $permissionName]);
        }
    }

    public function addPermissionToSupervisor(int $id, array $arrayUsers){

        $permissionName = 'view-investment-' . $id;
        $users = User::whereIn('id', $arrayUsers)->get();
        foreach ($users as $user) {
            $user->givePermissionTo($permissionName);
        }
    }

    public function sendMessageToInvestmentSupervisors(Investment $investment, $message = null)
    {
        $supervisors = json_decode($investment->supervisors, true);

        if (json_last_error() === JSON_ERROR_NONE && !empty($supervisors)) {
            foreach ($supervisors as $supervisor) {
                if (isset($supervisor['value']) && filter_var($supervisor['value'], FILTER_VALIDATE_EMAIL)) {
                    $email = $supervisor['value'];
                    $notificationMessage = 'W inwestycji ['.$investment->name.'] dokonano zmian.';

                    Notification::route('mail', $email)->notify(new SupervisorNotification(
                        '[DeveloPro] Aktualizacja inwestycji: ' . $investment->name,
                        $notificationMessage, $message
                    ));
                }
            }
        }
    }

    public function searchRooms(Investment $investment, Request $request)
    {

        $query = $investment->load(array(
            'searchProperties' => function ($query) use ($request) {
                if ($request->input('rooms')) {
                    $query->where('rooms', $request->input('rooms'));
                }
                if ($request->input('status')) {
                    $query->where('status', $request->input('status'));
                }
                if ($request->input('area_from')) {
                    $query->where('area', '>=', $request->input('area_from'));
                }
                if ($request->input('area_to')) {
                    $query->where('area', '<=', $request->input('area_to'));
                }
                if ($request->input('name')) {
                    $query->where('name', 'LIKE', '%'.$request->input('name').'%');
                }
            }
        ));

        return $query->searchProperties;
    }

    public function getDataTable(Investment $investment = null, string $minDate = null, string $maxDate = null){

        $query = Activity::orderByDesc("id");

        if ($investment) {
            $query->where('subject_type', 'App\Models\Investment');
            $query->where('subject_id', $investment->id);
        }

        if ($minDate) {
            $minDateCarbon = Carbon::parse($minDate)->startOfDay();
            $query->where('created_at', '>=', $minDateCarbon);
        }

        if ($maxDate) {
            $maxDateCarbon = Carbon::parse($maxDate)->endOfDay();
            $query->where('created_at', '<=', $maxDateCarbon);
        }

        $list = $query->get();

        return Datatables::of($list)
            ->editColumn('name', function ($row){
                return '<span data-filter="'. $row->causer->email.'">'.$row->causer->name.'<br>'.$row->causer->email.'</span>';
            })
            ->editColumn('method', function ($row){
                return '<span data-filter="'. $row->properties['methodType'].'"><div class="badge badge-method badge-method-'.strtolower($row->properties['methodType']).'">'. $row->properties['methodType'].'</div></span>';
            })
            ->editColumn('route', function ($row){
                return $row->properties['route'];
            })
            ->editColumn('referer', function ($row){
                return $row->properties['referer'];
            })
            ->editColumn('ip', function ($row){
                return $row->properties['ipAddress'];
            })
            ->editColumn('created_at', function ($row){
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $now = Carbon::now()->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();

                if($date >= $now){
                    return '<span>'.$date.'</span>';
                } else {
                    return '<span>'.$date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
                }
            })
            ->rawColumns(['method', 'name', 'created_at'])
            ->make(true);
    }

    public function getEventsAsTable(Investment $investment, Request $request)
    {

        $query = Event::where("investment_id", $investment->id);

        if ($request->filled('minDate')) {
            $minDateCarbon = Carbon::parse($request->input('minDate'))->startOfDay();
            $query->where('created_at', '>=', $minDateCarbon);
        }

        if ($request->filled('maxDate')) {
            $maxDateCarbon = Carbon::parse($request->input('maxDate'))->endOfDay();
            $query->where('created_at', '<=', $maxDateCarbon);
        }

        $list = $query->with(['client', 'user'])
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
                return status($row->active);
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
}
