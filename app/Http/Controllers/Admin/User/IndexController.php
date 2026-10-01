<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\Department;
use App\Notifications\PublicNotification;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// CMS
use App\Http\Requests\UserFormRequest;
use App\Repositories\UserRepositoryInterface;
use App\Models\User;

//Importing laravel-permission models
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class IndexController extends Controller
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {

        $this->middleware('permission:user-list|user-create|user-edit|user-delete', [
            'only' => ['index','store']
        ]);
        $this->middleware('permission:user-create', [
            'only' => ['create','store']
        ]);
        $this->middleware('permission:user-edit|user-self', [
            'only' => ['edit','update']
        ]);
        $this->middleware('permission:user-delete', [
            'only' => ['destroy']
        ]);
        $this->repository = $repository;
    }

    public function index()
    {
       return view('admin.user.index', [
            'list' => $this->repository->all()
        ]);
    }

    public function create()
    {
        return view('admin.user.form', [
            'cardTitle' => 'Nowy użytkownik',
            'roles' => $this->repository->getRoles(),
            'cities' => CustomField::where('group_id', 1)->pluck('value', 'id')->prepend('--- brak ---', 0),
            'job_positions' => Department::all()->pluck('name', 'id'),
            'backButton' => route('admin.user.index'),
            'selected' => ''
        ])->with('entry', User::make());
    }

    public function store(UserFormRequest $request)
    {
        $user = User::create($request->merge([
            'password' => Hash::make($request->get('password')),
        ])->except(['_token', 'submit', 'confirm-password', 'roles']));

        $user->assignRole($request->input('roles'));

        return redirect(route('admin.user.index'))->with('success', 'Użytkownik dodany');
    }

    public function edit($id)
    {

        $user = $this->repository->find($id);
        $userRole = $user->roles->pluck('name', 'name')->all();


        // If the user has 'user-edit', allow editing any account
        if (Auth::user()->can('user-edit')) {
            return view('admin.user.form', [
                'cardTitle' => $user->name .' '.$user->surname,
                'roles' => $this->repository->getRoles(),
                'cities' => CustomField::where('group_id', 1)->pluck('value', 'id')->prepend('--- brak ---', 0),
                'job_positions' => Department::all()->pluck('name', 'id'),
                'selected' => $userRole,
                'backButton' => route('admin.user.index'),
                'entry' => $user
            ]);
        }

        // If the user has 'user-self', allow editing only their own account
        if (Auth::user()->can('user-self') && $user->id === Auth::id()) {
            return view('admin.user.form', [
                'cardTitle' => $user->name .' '.$user->surname,
                'roles' => $this->repository->getRoles(),
                'cities' => CustomField::where('group_id', 1)->pluck('value', 'id')->prepend('--- brak ---', 0),
                'job_positions' => Department::all()->pluck('name', 'id'),
                'selected' => $userRole,
                'backButton' => route('admin.user.index'),
                'entry' => $user
            ]);
        }

        // Otherwise, deny access
        abort(403, 'Unauthorized action.');
    }

    public function update(UserFormRequest $request, $id)
    {
        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = $this->repository->find($id);
        $user->update($input);

        if(Auth::user()->hasRole('Administrator')) {
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles'));
        }

        //$user->update($request->except(['_token', 'submit']));
        return redirect(route('admin.user.index'))->with('success', 'Użytkownik zaktualizowany');
    }

    public function datatable(Request $request){
        $query = User::orderByDesc('name');

        if ($request->filled('minDate')) {
            $minDate = Carbon::parse($request->input('minDate'))->startOfDay();
            $query->where('created_at', '>=', $minDate);
        }

        if ($request->filled('maxDate')) {
            $maxDate = Carbon::parse($request->input('maxDate'))->endOfDay();
            $query->where('created_at', '<=', $maxDate);
        }

        if ($request->filled('department')) {
            $department = $request->input('department');
            $query->where('job_position', '=', $department);
        }

        $list = $query->with('department')->get();

        return Datatables::of($list)
            ->editColumn('job_position', function ($row){
                if($row->department)
                {
                    return $row->department->name;
                }
            })
            ->editColumn('status', function ($row){
                return status($row->active);
            })
            ->editColumn('created_at', function ($row){
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();
                return '<span>'.$date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
            })
            ->editColumn('updated_at', function ($row){
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();
                return '<span>'.$date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
            })
            ->addColumn('actions', function ($row) {
                return view('admin.user.tableActions', ['row' => $row]);
            })
            ->rawColumns([
                'status',
                'created_at',
                'updated_at',
                'actions'
            ])
            ->make();
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }

    public function sendNotification()
    {
        $message = 'This is a public notification';
        broadcast(new PublicNotification($message))->toOthers();

        \Log::info('Notification event dispatched.');
    }

    public function showAllRoles()
    {
        $permissions = Auth::user()->getPermissionsViaRoles()->pluck('name'); // Extract only the names
        dd($permissions);
    }
}
