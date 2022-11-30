<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PermissionDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * PermissionController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:read-permissions');
        $this->middleware('permission:add-permissions', ['only' => ['create']]);
        $this->middleware('permission:update-permissions', ['only' => ['edit']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param PermissionDataTable $dataTable
     * @return Response
     */
    public function index(PermissionDataTable $dataTable)
    {
        return $dataTable->render('admin.permission.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        return view('admin.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'alias' => 'required|string|max:50'
        ]);

        Permission::create(['alias' => 'read-' . Str::slug(request('alias')), 'name' => 'read-' . Str::slug(request('alias'))]);
        Permission::create(['alias' => 'add-' . Str::slug(request('alias')), 'name' => 'add-' . Str::slug(request('alias'))]);
        Permission::create(['alias' => 'update-' . Str::slug(request('alias')), 'name' => 'update-' . Str::slug(request('alias'))]);
        Permission::create(['alias' => 'delete-' . Str::slug(request('alias')), 'name' => 'delete-' . Str::slug(request('alias'))]);

        return redirect()->route('permissions.index')->withSuccess(__('Permission saved successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
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
        $permission = Permission::findOrFail($id);
        return view('admin.permission.edit', compact('permission'));
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
        $request->validate([
            'alias' => 'required|string|max:50'
        ]);
        $permission = Permission::findOrFail($id);
        $permission->update([
            'alias' => $request->alias
        ]);
        return redirect()->route('permissions.index')
            ->withSuccess(__('Permission changed successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::allows('delete-permissions')) {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            return response()->json(['success' => __('Deleted successfully.')]);
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
        if (Gate::allows('delete-permissions')) {
            $permissions_id_array = request('id');
            $permissions = Permission::whereIn('id', $permissions_id_array);
            if($permissions->delete()) {
                return response()->json(['success' => __('Deleted successfully.')]);
            } else {
                return response()->json(['error' => __('Deleted not successfully.')]);
            }
        } else {
            return response()->json(['error' => __('Sorry, you don\'t have permission.')]);
        }
    }
}
