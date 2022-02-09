<?php

namespace App\Http\Controllers;

use App\Models\Navigation;
use Illuminate\Http\Request;
use DataTables;
use Spatie\Permission\Models\Permission;

class NavigationController extends Controller
{

    public function index()
    {
        $navigations = Navigation::whereNotNull('url')->get();
    }

    public function create(Request $request)
    {
        $title = "Create Navigation";
        $permissions = Permission::get();
        $navigations = Navigation::where('url', null)->get();
        $navigation = Navigation::whereNotNull('url')->get();
        if ($request->ajax()) {
            $allData = DataTables::of($navigation)
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
        // dd($navigation);
        return view('navigation.create', compact('title', 'permissions', 'navigations', 'navigation'));
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
            'permission_name' => 'required'
        ]);

        Navigation::create([
            'name' => request('name'),
            'url' => request('url') ?? null,
            'parent_id' => request('parent_id') ?? null,
            'permission_name' => request('permission_name')
        ]);

        return back();
    }
}
