<?php

namespace App\Http\Controllers;

use App\Models\ShiftWorkModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ShiftWorkController extends Controller
{
    public function index(Request $request)
    {
        $title = "Shift Work Data";
        $shiftwork = ShiftWorkModel::get();
        // dd($machine);
        if ($request->ajax()) {
            $allData = DataTables::of($shiftwork)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row->id);
                    $btn = '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editShiftWork">Edit</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteShiftWork">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('shiftwork.index', compact('shiftwork', 'title'));
    }

    public function store(Request $request)
    {

        ShiftWorkModel::updateOrCreate(
            ['id' => $request->id],
            [
                'shiftwork' => $request->shiftwork,
                'checkIn' => $request->checkIn,
                'checkOut' => $request->checkOut,
            ]
        );

        return response()->json(['success' => 'Machine Added Successfully!']);
    }

    public function edit($id)
    {
        $department = ShiftWorkModel::find($id);
        return response()->json($department);
    }

    public function destroy($id)
    {
        ShiftWorkModel::find($id)->delete();
        return response()->json(['succes' => 'Department Delete Successfully!']);
    }
}
