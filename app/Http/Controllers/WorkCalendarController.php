<?php

namespace App\Http\Controllers;

use App\Models\WorkCalendarModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WorkCalendarController extends Controller
{
    public function index(Request $request)
    {
        $title = "Work Calendar Data";
        $workcalendar = WorkCalendarModel::get();
        // dd($machine);
        if ($request->ajax()) {
            $allData = DataTables::of($workcalendar)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row->id);
                    $btn = '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editworkcalendar">Edit</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteworkcalendar">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('workcalendar.index', compact('workcalendar', 'title'));
    }

    public function store(Request $request)
    {

        WorkCalendarModel::updateOrCreate(
            ['id' => $request->id],
            [
                'date' => $request->date,
                'description' => $request->description,
            ]
        );

        return response()->json(['success' => 'Machine Added Successfully!']);
    }

    public function edit($id)
    {
        $workcalendar = WorkCalendarModel::find($id);
        return response()->json($workcalendar);
    }

    public function destroy($id)
    {
        WorkCalendarModel::find($id)->delete();
        return response()->json(['succes' => 'Department Delete Successfully!']);
    }
}
