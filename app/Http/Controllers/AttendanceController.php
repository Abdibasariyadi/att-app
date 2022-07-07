<?php

namespace App\Http\Controllers;


use App\Models\AttendanceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use ZKLibrary;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $title = "Attendance Logs";
        $attendance = AttendanceModel::getAttendance();
        if ($request->ajax()) {
            $allData = DataTables::of($attendance)
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

        return view('attendance.index', compact('title'));
    }

    public function getAttendance()
    {
        $zk = new zklibrary('192.168.1.201', 4370);
        $zk->connect();
        $zk->disableDevice();
        $getAttendance = $zk->getAttendance();

        echo "<pre>";
        echo $getAttendance;
        echo "</pre>";

        foreach ($getAttendance as $key => $row) {

            $data = array(
                'uid' => $row[1],
                'time' => $row[3]
            );

            AttendanceModel::insert($data);
        }

        $zk->enableDevice();
        $zk->disconnect();
    }
}
