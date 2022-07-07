<?php

namespace App\Http\Controllers;

use App\Models\positionModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $title = "Position Data";
        $position = positionModel::get();
        // dd($machine);
        if ($request->ajax()) {
            $allData = DataTables::of($position)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row->id);
                    $btn = '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPosition">Edit</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deletePosition">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('position.index', compact('position', 'title'));
    }

    public function store(Request $request)
    {

        positionModel::updateOrCreate(
            ['id' => $request->id],
            [
                'position_id' => $request->position_id,
                'name' => $request->name,
                'salary_position' => $request->salary_position
            ]
        );

        return response()->json(['success' => 'Machine Added Successfully!']);
    }

    public function edit($id)
    {
        $department = positionModel::find($id);
        return response()->json($department);
    }

    public function destroy($id)
    {
        positionModel::find($id)->delete();
        return response()->json(['succes' => 'Department Delete Successfully!']);
    }
}
