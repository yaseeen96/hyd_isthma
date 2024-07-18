<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->id != 1 && auth()->user()->hasPermissionTo('View Permissions')){
            abort(403);
        }

        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('admin.permissions.list')->with(['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->id != 1 && auth()->user()->hasPermissionTo('Create Permissions')){
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->id != 1 && auth()->user()->hasPermissionTo('View Permissions')){
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        if (!auth()->user()->id != 1 && auth()->user()->hasPermissionTo('Edit Permissions')){
            abort(403);
        }

        $role = Role::findById($id);
        return view('admin.permissions.form')->with(
            [
                "role" => $role,
                "permissions" => Permission::all(),
                "role_permissions" => $role->permissions()->get()->pluck('name')->toArray()
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findById($id);
        $role->syncPermissions($request->input('permissions', []));
        return redirect()->route('permissions.edit',$id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
