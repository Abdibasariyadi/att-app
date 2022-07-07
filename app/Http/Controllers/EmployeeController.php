<?php

namespace App\Http\Controllers;

use App\Models\departemenModel;
use App\Models\EmployeeModel;
use App\Models\OvertimeModel;
use App\Models\positionModel;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $title = "Employee Data";
        $employee = EmployeeModel::get();
        // var_dump($department);
        // die;
        if ($request->ajax()) {
            $employee = EmployeeModel::getEmployee();
            return DataTables::eloquent($employee)
                ->addColumn('photo', function ($employee) {
                    $url = asset('uploads/' . $employee->photo);
                    return '<img src="' . $url . '" border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd($row->id);
                    $btn = '<a href="' . url('employee/' . $row->uid . '/attendance') . '"  data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-warning btn-sm">Attendance</a> | ';
                    $btn .= '<a href="' . url('employee/' . $row->uid . '/shiftwork') . '"  data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-success btn-sm">Shift Type</a> | ';
                    $btn .= '<a href="' . url('employee/' . $row->uid) . '/edit"  data-toogle="tooltip" data-id="' . $row->uid . '" data-original-title="Edit" class="edit btn btn-primary btn-sm">Edit</a> | ';
                    $btn .= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteEmployee">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['photo', 'action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('employee.index', compact('title', 'employee'));
    }

    public function create()
    {
        $title = "Create Employee";
        $department = departemenModel::get();
        $position = positionModel::get();
        return view('employee.create', compact('department', 'title', 'position'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'uid' => 'required|max:10',
            'name' => 'required',
            'email' => 'required',
            'dateOfBirth' => 'required',
            'gender' => 'required',
        ]);

        if ($request->hasFile('photo')) {
            // upload photo
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $destinationPath = 'uploads';
            $file->move($destinationPath, $fileName);
        } else {
            $fileName = null;
        }

        $employee  = new EmployeeModel();
        $employee->uid                 = $request->uid;
        $employee->name        = $request->name;
        $employee->email               = $request->email;
        $employee->dateOfBirth    = $request->dateOfBirth;
        $employee->gender        = $request->gender;
        $employee->department_id      = $request->department_id;
        $employee->position_id      = $request->position_id;
        $employee->salary      = $request->salary;
        $employee->photo                 = $fileName;
        $employee->save();
        return redirect('employee')->with('message', 'Berhasil Menyimpan Data employee');
    }

    public function edit($uid)
    {
        $title = "Edit Employee Data";
        $department = departemenModel::get();
        $position = positionModel::get();
        $employee = DB::table('employee')->where('uid', $uid)->first();
        return view('employee.edit', compact('employee', 'title', 'department', 'position'));
    }

    public function update(Request $request, $uid)
    {

        $employee = EmployeeModel::where('uid', $uid)->first();
        $employee->uid                 = $request->uid;
        $employee->name        = $request->name;
        $employee->email               = $request->email;
        $employee->dateOfBirth    = $request->dateOfBirth;
        $employee->gender        = $request->gender;
        $employee->department_id      = $request->department_id;
        $employee->salary      = $request->salary;

        if ($request->hasFile('photo')) {
            // upload photo
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $destinationPath = 'uploads';
            $file->move($destinationPath, $fileName);
            $employee->photo                 = $fileName;
        }

        $employee->update();

        return redirect('employee')->with('message', 'Berhasil Menyimpan Perubahan Data employee');
        // return response()->json(['success' => 'Employee Update Successfully!']);
    }

    public function destroy($uid)
    {
        EmployeeModel::find($uid)->delete();
        return response()->json(['succes' => 'Department Delete Successfully!']);
    }

    public function shiftWork_employee(Request $request, $uid)
    {
        $title = "Shift Work Employee";
        $employee = DB::table('employee')->where('uid', $uid)->first();
        $shiftwork_employee =    DB::table('shiftwork_employee')
            ->join('shiftwork', 'shiftwork.id', '=', 'shiftwork_employee.shiftwork_id')
            ->where('shiftwork_employee.uid', $uid)
            ->orderBy('shiftwork_employee.date', 'ASC')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($shiftwork_employee)
                ->addIndexColumn()
                ->addIndexColumn()
                ->make(true);
        }

        return view('employee.shiftwork', compact('title', 'employee'));
    }

    public function attendance_employee(Request $request, $uid)
    {
        $data['title'] = "Attendance History Employee";
        $attHistory =   DB::table('view_attendancehistory')->where('uid', $uid)->orderBy('date', 'DESC')->get();
        if ($request->ajax()) {
            return DataTables::of($attHistory)
                ->addIndexColumn()
                ->addIndexColumn()
                ->make(true);
        }
        $data['employee'] =  DB::table('employee')->where('uid', $uid)->first();
        return view('employee.attendance', $data);
    }

    public function overtime_employee(Request $request, $uid)
    {
        $data['title'] = "Overtime History Employee";
        $overTimeHistory = DB::table('view_attendancehistory')->get();
        if ($request->ajax()) {
            return DataTables::of($overTimeHistory)
                ->addIndexColumn()
                ->addColumn('overTime', function ($row) {
                    // $overTime = strtotime($row->logoutTime) - strtotime($row->checkOut) / 60;
                    $diff = strtotime($row->logoutTime) - strtotime($row->checkOut);
                    $overTime = date('i:s', $diff);
                    return $overTime;
                })
                ->make(true);
        }
        $data['employee'] =  DB::table('employee')->where('uid', $uid)->first();

        return view('employee.overtime', $data);
    }

    public function filterOverTime(Request $request, $uid)
    {
        $min = date('Y-m-d 00:00:00', strtotime($request->get('min')));
        $max = date('Y-m-d 23:59:59', strtotime($request->get('max')));

        if (!empty($min) && !empty($max)) {
            $presence = EmployeeModel::filterOverTime($min, $max, $uid);
            return Datatables::of($presence)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function filterAtt(Request $request, $uid)
    {
        $min = date('Y-m-d 00:00:00', strtotime($request->get('min')));
        $max = date('Y-m-d 23:59:59', strtotime($request->get('max')));

        if (!empty($min) && !empty($max)) {
            $presence = EmployeeModel::filterAtt($min, $max, $uid);
            return Datatables::of($presence)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function filterShift(Request $request, $uid)
    {
        $min = date('Y-m-d 00:00:00', strtotime($request->get('min')));
        $max = date('Y-m-d 23:59:59', strtotime($request->get('max')));

        if (!empty($min) && !empty($max)) {
            $presence = EmployeeModel::filterShift($min, $max, $uid);
            return Datatables::of($presence)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
