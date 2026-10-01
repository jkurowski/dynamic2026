<?php

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class IndexController extends Controller
{
    function __construct(){
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('admin.role.index', ['list' => Role::all()]);
    }

    public function create()
    {
        return view('admin.role.form', [
            'cardTitle' => 'Dodaj grupę',
            'permission' => Permission::get(),
            'backButton' => route('admin.role.index'),
            'rolePermissions' => array()
        ])->with('entry', Role::make());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required'
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('admin.role.index')->with('success','Grupa utworzona');
    }

    public function edit($id)
    {

        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('admin.role.form', [
            'cardTitle' => 'Edytuj grupę',
            'permission' => Permission::get(),
            'backButton' => route('admin.role.index'),
            'rolePermissions' => $rolePermissions,
            'entry' => Role::find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('admin.role.index')->with('success','Grupa zapisana');
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        Session::flash('success', 'Grupa usunięta');
        return response()->json('Deleted', 200);
    }
}
