<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DataTables;

class AssignController extends Controller
{
    public function create(Request $request)
    {
        return view('permission.assign.create', [
            'title' => "Assign Permission",
            'roles' => Role::get(),
            'permissions' => Permission::get()
        ]);

        return view('permission.assign.create', compact('roles', 'permissions', 'title'));
    }

    public function store()
    {
        request()->validate([
            'role' => 'required',
            'permissions' => 'array|required'
        ]);

        // dd(request('permissions'));

        $role = Role::find(request('role'));
        $role->givePermissionTo(request('permissions'));

        return back()->with('success', "Permissions success assign to the Role! {$role->name}");
    }

    public function edit(Role $role)
    {
        return view('permission.assign.sync', [
            'title' => "Sync Permission",
            'role' => $role,
            'roles' => Role::get(),
            'permissions' => Permission::get()
        ]);
    }

    public function update(Role $role)
    {
        request()->validate([
            'role' => 'required',
            'permissions' => 'array|required'
        ]);

        $role->syncPermissions(request('permissions'));

        return redirect()->route('assigns.create')->with('success', 'The permissions has been synced.');
    }
}
