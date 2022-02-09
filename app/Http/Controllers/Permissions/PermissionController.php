<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use DataTables;

// use Yajra\DataTables\Contracts\DataTable;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $title = "Permission";
        $permissions = Permission::get();
        if ($request->ajax()) {
            $allData = DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPermission">Edit</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="edit btn btn-danger btn-sm deletePermission">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('permission.permissions.index', compact('permissions', 'title'));
    }

    public function store(Request $request)
    {
        Permission::updateOrCreate(
            ['id' => $request->role_id],
            [
                'name' => $request->name,
                'guard_name' => $request->guard_name ?? 'web'
            ]
        );

        // $role = Permission::where('id', request('role_id'))->first();
        // var_dump($role); die;
        // if($role !== null) {
        //     $role->update([
        //         'name' => request('name'),
        //         'guard_name' => request('guard_name')
        //     ]);
        // } else {
        //     $role = Permission::create([
        //         'id' => request('role_id'),
        //         'name' => request('name'),
        //         'guard_name' => request('guard_name')
        //     ]);
        // }

        return response()->json(['succes' => 'Permission Added Successfully!']);
    }

    public function edit($id)
    {
        $role = Permission::find($id);
        return response()->json($role);
    }

    public function destroy($id)
    {
        Permission::find($id)->delete();
        return response()->json(['succes' => 'Permission Delete Successfully!']);
    }
}
