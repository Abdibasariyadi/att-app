<?php

namespace App\Http\Controllers;

use App\Models\departemenModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DepartemenController extends Controller
{
    public function index(Request $request)
    {
        $title = "Departments Data";
        $department = departemenModel::get();
        // dd($machine);
        if ($request->ajax()) {
            $allData = DataTables::of($department)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row->id);
                    $btn = '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editdepartment">Edit</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteDepartment">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('department.index', compact('department', 'title'));
    }

    public function store(Request $request)
    {

        departemenModel::updateOrCreate(
            ['id' => $request->id],
            [
                'department_id' => $request->department_id,
                'name' => $request->name,
            ]
        );

        return response()->json(['success' => 'Machine Added Successfully!']);
    }

    public function edit($id)
    {
        $department = departemenModel::find($id);
        return response()->json($department);
    }

    public function destroy($id)
    {
        departemenModel::find($id)->delete();
        return response()->json(['succes' => 'Department Delete Successfully!']);
    }
}
