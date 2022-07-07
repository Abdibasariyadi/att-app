<?php

namespace App\Http\Controllers;

use App\Models\MachineSetModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MachineSet extends Controller
{
    public function index(Request $request)
    {
        $title = "Machine Setting";
        $machine = MachineSetModel::get();
        // dd($machine);
        if ($request->ajax()) {
            $allData = DataTables::of($machine)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row->id);
                    $btn = '<a href="' . url('anggota/edit/' . $row->id) . '" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editRole">Edit</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteAnggota">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('machine.index', compact('machine', 'title'));
    }

    public function store(Request $request)
    {
        MachineSetModel::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'ipAddress' => $request->ipAddress,
                'port' => $request->port,
            ]
        );

        return response()->json(['success' => 'Machine Added Successfully!']);
    }

    public function edit($id)
    {
        $machine = MachineSetModel::find($id);
        return response()->json($machine);
    }

    public function destroy($id)
    {
        MachineSetModel::find($id)->delete();
        return response()->json(['success' => 'Machine Delete Successfully!']);
    }
}
