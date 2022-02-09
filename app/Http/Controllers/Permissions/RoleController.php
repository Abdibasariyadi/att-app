<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use DataTables;
// use Yajra\DataTables\Contracts\DataTable;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $title = "Role & Permissions";
        $role = Role::get();
        if ($request->ajax()) {
            $allData = DataTables::of($role)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editRole">Edit</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="edit btn btn-danger btn-sm deleteRole">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('permission.roles.index', compact('role', 'title'));
    }

    public function store(Request $request)
    {
        Role::updateOrCreate(
            ['id' => $request->role_id],
            [
                'name' => $request->name,
                'guard_name' => $request->guard_name ?? "web"
            ]
        );

        return response()->json(['succes' => 'Role Added Successfully!']);
    }

    public function edit($id)
    {
        $role = Role::find($id);
        return response()->json($role);
    }

    public function destroy($id)
    {
        Role::find($id)->delete();
        return response()->json(['succes' => 'Role Delete Successfully!']);
    }
}
