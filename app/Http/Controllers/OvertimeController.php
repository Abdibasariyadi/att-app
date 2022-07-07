<?php

namespace App\Http\Controllers;

use App\Models\OvertimeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $title = "Overtime Data";
        $overtime = DB::table('view_attendancehistory')->get();
        // dd($overtime);
        if ($request->ajax()) {
            $allData = DataTables::of($overtime)
                ->addIndexColumn()
                ->addColumn('overTime', function ($row) {
                    // $overTime = strtotime($row->logoutTime) - strtotime($row->checkOut) / 60;
                    $diff = strtotime($row->logoutTime) - strtotime($row->checkOut);
                    $overTime = date('i:s', $diff);
                    return $overTime;
                })
                ->make(true);
            return $allData;
        }

        return view('overtime.index', compact('overtime', 'title'));
    }

    public function filter(Request $request)
    {
        $min = date('Y-m-d 00:00:00', strtotime($request->get('min')));
        $max = date('Y-m-d 23:59:59', strtotime($request->get('max')));

        if (!empty($min) && !empty($max)) {
            $presence = OvertimeModel::filterOvertime($min, $max);
            return Datatables::of($presence)
                ->addColumn('overTime', function ($row) {
                    // $overTime = strtotime($row->logoutTime) - strtotime($row->checkOut) / 60;
                    $diff = strtotime($row->logoutTime) - strtotime($row->checkOut);
                    $overTime = date('i:s', $diff);
                    return $overTime;
                })
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $employee = DB::table('employee')->where('name', $request->name)->first();

        // OvertimeModel::updateOrCreate(
        //     ['id' => $request->id],
        //     [
        //         'uid' => $employee->uid,
        //         'checkIn' => $request->checkIn,
        //         'checkOut' => $request->checkOut,
        //         'duration' => $request->duration
        //     ]
        // );
        $data = [
            'uid' => $employee->uid,
            'checkIn' => $request->checkIn,
            'checkOut' => $request->checkOut,
            'duration' => $request->duration
        ];
        DB::table('overtime')->insert($data);
        return response()->json(['success' => 'Overtime Added Successfully!']);
    }

    public function edit($id)
    {
        $department = OvertimeModel::find($id);
        return response()->json($department);
    }

    public function destroy($id)
    {
        OvertimeModel::find($id)->delete();
        return response()->json(['succes' => 'Department Delete Successfully!']);
    }
}
