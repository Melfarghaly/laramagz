<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * RoleController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:read-roles', ['except' => ['ajaxSearch']]);
        $this->middleware('permission:add-roles', ['only' => ['create']]);
        $this->middleware('permission:update-roles', ['only' => 'edit']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->get('q');
        return User::searchRole($keyword);
    }

    /**
     * @return JsonResponse
     */
    public function changePermission()
    {
        $role = Role::findOrFail(request('role_id'));

        $permission = Permission::find(request('permissions'));
        if ( $role->hasPermissionTo($permission->name) ) {
            $role->revokePermissionTo($permission->name);
            $msg = __('Revoke ' . $permission->alias . ' permissions');
        } else {
            $role->givePermissionTo($permission->name);
            $msg = __('Give ' . $permission->alias . ' permissions');
        }
        return response()->json(['success' => $msg]);
    }

    /**
     * @return JsonResponse
     */
    public function changeAllPermission()
    {
        $role = Role::findOrFail(request('role_id'));

        if ( request('status') === 'true' ) {
            $role->givePermissionTo(Permission::all());
            $msg = __('Give all permissions');
        } else {
            $role->revokePermissionTo(Permission::all());
            $msg = __('Revoke all permissions');
        }

        return response()->json(['success' => $msg]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param RoleDataTable $dataTable
     * @return Response
     */
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:roles|max:50|min:2|alpha_dash'
        ]);
        Role::firstOrCreate(['name' => Str::lower($request->name)]);

        $read = Permission::create(['alias' => 'read-' . Str::lower($request->name), 'name' => 'read-' . Str::lower($request->name)]);
        $add = Permission::create(['alias' => 'add-' . Str::lower($request->name), 'name' => 'add-' . Str::lower($request->name)]);
        $update = Permission::create(['alias' => 'update-' . Str::lower($request->name), 'name' => 'update-' . Str::lower($request->name)]);
        $delete = Permission::create(['alias' => 'delete-' . Str::lower($request->name), 'name' => 'delete-' . Str::lower($request->name)]);

        Role::findByName('superadmin')->givePermissionTo([
            $read, $add, $update, $delete
        ]);
        Role::findByName('admin')->givePermissionTo([
            $read, $add, $update, $delete
        ]);

        return redirect()->route('roles.index')->withSuccess(__('Role saved successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        if(Auth::User()->hasRole('superadmin')) {
            $permissions = Permission::all()->pluck('alias', 'id');
        }else{
            $permission = Permission::all()->except([61, 62, 63, 64]);
            $permissions = $permission->pluck('alias', 'id');
        }

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        $ifCheckAll = Permission::count() === count($rolePermissions);

        return view('admin.role.edit', compact('role', 'permissions', 'rolePermissions', 'ifCheckAll'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|' . Rule::unique('roles')->ignore($id, 'id') .'|max:50|min:2|alpha_dash',
        ]);
        $role = Role::findOrFail($id);
        $role->name = Str::lower(request('name'));
        $role->save();
        return redirect()->route('roles.index')
            ->withSuccess(__('Role Updating successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::allows('delete-roles')) {
            $role = Role::findOrFail($id);
            Permission::where('alias', 'read-' . $role->name)->delete();
            Permission::where('alias', 'add-' . $role->name)->delete();
            Permission::where('alias', 'update-' . $role->name)->delete();
            Permission::where('alias', 'delete-' . $role->name)->delete();
            $role->delete();
            return response()->json(['success' => __('Role deleted successfully.')]);
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }

    /**
     * Remove the multi resource from storage.
     *
     * @return JsonResponse
     */
    public function massdestroy()
    {
        if (Gate::allows('delete-roles')) {
            $roles_id_array = request('id');
            $roles = Role::whereIn('id', $roles_id_array);

            $permission_id_array = [];

            foreach($roles->get() as $role) {
                $permission_id_array[] = Permission::where('alias', 'read-' . $role->name)->first()->id;
                $permission_id_array[] = Permission::where('alias', 'add-' . $role->name)->first()->id;
                $permission_id_array[] = Permission::where('alias', 'update-' . $role->name)->first()->id;
                $permission_id_array[] = Permission::where('alias', 'delete-' . $role->name)->first()->id;
            }

            Permission::whereIn('id', $permission_id_array)->delete();

            if($roles->delete()) {
                return response()->json(['success' => __('Role deleted successfully.')]);
            } else {
                return response()->json(['error' => __('Role deleted not successfully.')]);
            }
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }
}
