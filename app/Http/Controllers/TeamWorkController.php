<?php

namespace App\Http\Controllers;

use App\Models\employee_group_work_model;
use App\Models\ShiftWorkModel;
use App\Models\TeamWorkModel;
use App\Models\WorkingGroupModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TeamWorkController extends Controller
{
    public function index(Request $request)
    {
        $title = "Team Work Data";
        $teamwork = TeamWorkModel::get();
        $shiftWork = ShiftWorkModel::get();
        // dd($machine);
        if ($request->ajax()) {
            $allData = DataTables::of($teamwork)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row->id);
                    $btn = '<a href="' . url('teamwork/' . $row->id) . '/shiftwork" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-success btn-sm addTeamWork">Shift Work</a> | ';
                    $btn .= '<a href="' . url('teamwork/' . $row->id) . '" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-info btn-sm addTeamWork">Team</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editTeamWork">Edit</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteTeamWork">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('teamwork.index', compact('teamwork', 'title', 'shiftWork'));
    }

    public function show($id)
    {
        $data['title'] = "Working Group Members";
        $data['teamWork'] = TeamWorkModel::find($id);
        $data['groupWork'] = DB::table('workinggroup')
            ->select('workinggroup.id as id', 'employee.uid as uid', 'employee.name as name')
            ->leftJoin('employee', 'employee.uid', '=', 'workinggroup.uid')
            ->where('workinggroup.teamWork_id', $id)
            ->get();
        // return $data['groupWork'];

        return view('teamwork.show', $data);
    }

    public function shiftwork(Request $request, $id)
    {
        $data['title'] = "Employee Group Work Pattern";
        $data['teamWork'] = TeamWorkModel::find($id);
        $data['shiftWork'] = ShiftWorkModel::get();
        $shiftworkTable = DB::table('employee_group_work')
            ->select('employee_group_work.id', 'employee_group_work.date', 'employee_group_work.id', 'shiftwork.shiftwork', 'shiftwork.checkIn', 'shiftwork.checkOut')
            ->join('shiftwork', 'shiftwork.id', '=', 'employee_group_work.shiftwork_id')->get();


        if ($request->ajax()) {
            $allData = DataTables::of($shiftworkTable)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row->id);
                    $btn = '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-danger btn-sm deleteshiftworkTable">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            return $allData;
        }

        return view('teamwork.shiftwork', $data);
    }


    public function shiftworkSave(Request $request)
    {
        $period = new \DatePeriod(
            new \DateTime($request->startDate),
            new \DateInterval('P1D'),
            new \DateTime(date('Y-m-d', strtotime('1 day', strtotime($request->endDate))))
        );

        $dataTeamWork = [];

        // insert pola kerja kelompok karyawan
        foreach ($period as $key => $value) {
            $dataTeamWork[] = [
                'date' => $value->format('Y-m-d'),
                'shiftwork_id' => $request->shiftWork,
                'teamwork_id' => $request->teamWork,
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ];
        }

        DB::table('employee_group_work')->insert($dataTeamWork);


        // insert pola kerja karyawan
        $dataEmployee = [];

        $employee = DB::table('workinggroup')
            ->where('teamWork_id', $request->teamWork)
            ->get();

        foreach ($employee as $k) {
            foreach ($period as $key => $value) {
                $dataEmployee[] = [
                    'uid'           =>  $k->uid,
                    'shiftwork_id' =>  $request->shiftWork,
                    'date'       =>  $value->format('Y-m-d'),
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ];
            }
        }

        DB::table('shiftwork_employee')->insert($dataEmployee);

        return response()->json(['success' => 'Employee Group Team Added Successfully!']);
    }

    public function shiftworkDelete($id)
    {
        // dd($polaKerja);
        // DB::table('employee_group_work')->where('id', $id)->delete();
        employee_group_work_model::find($id)->delete();


        return response()->json(['success' => 'Delete Successfully!']);
    }

    public function store(Request $request)
    {

        TeamWorkModel::updateOrCreate(
            ['id' => $request->id],
            [
                'TeamWorkName' => $request->TeamWorkName,
            ]
        );

        return response()->json(['success' => 'Team Work Added Successfully!']);
    }

    public function addTeamWork(Request $request)
    {
        // return $request->all();
        $employee = DB::table('employee')->where('name', $request->name)->first();

        $data = [
            'uid' => $employee->uid,
            'teamWork_id' => $request->id,
            'created_at' => Carbon::now()
        ];
        DB::table('workinggroup')->insert($data);
        return response()->json(['success' => 'Group Work Added Successfully!']);
    }

    public function edit($id)
    {
        $department = TeamWorkModel::find($id);
        return response()->json($department);
    }

    public function destroy($id)
    {
        TeamWorkModel::find($id)->delete();
        return response()->json(['success' => 'Team Work Delete Successfully!']);
    }

    public function destroyGroup($id)
    {
        $group = DB::table('workinggroup')
            ->join('employee', 'workinggroup.uid', '=', 'employee.uid')
            ->where('workinggroup.id', $id)
            ->first();

        DB::table('workinggroup')
            ->where('id', $id)
            ->delete();

        // return response()->json(['success' => 'Delete Successfully!']);
        return redirect('teamwork/' . $id)->with('message', 'Berhasil Menyimpan Perubahan Data employee');
    }
}
