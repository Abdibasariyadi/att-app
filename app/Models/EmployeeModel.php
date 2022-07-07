<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeModel extends Model
{
    use HasFactory;
    protected $table = 'employee';

    protected $fillable = [
        'uid',
        'name',
        'email',
        'dateOfBirth',
        'gender',
        'photo',
        'department_id'
    ];

    public function getEmployee()
    {
        return $getEmployee = EmployeeModel::leftJoin('departemen', 'departemen.department_id', '=', 'employee.department_id')
            ->leftJoin('position', 'position.position_id', '=', 'employee.position_id')
            ->select('employee.id', 'email', 'uid', 'employee.name', 'dateOfBirth', 'gender', 'photo', 'departemen.name as department_id', 'position.name as position_id')->orderBy('employee.id', 'DESC');
    }

    public function editEmployee($id)
    {
        return $editEmployee = EmployeeModel::leftJoin('departemen', 'departemen.department_id', '=', 'employee.department_id')
            ->leftJoin('position', 'position.position_id', '=', 'employee.position_id')
            ->select('employee.id', 'email', 'uid', 'employee.name', 'dateOfBirth', 'gender', 'photo', 'departemen.name as department_id', 'position.name as position_id')
            ->where('employee.id', '=', $id);
    }

    public function filterAtt($min, $max, $uid)
    {
        return $filterPresence = DB::table('view_attendancehistory')->whereBetween(DB::raw('DATE(date)'), array($min, $max))->where('uid', $uid)->orderBy('date', 'DESC')->get();
    }

    public function filterShift($min, $max, $uid)
    {
        return $shiftwork_employee = DB::table('shiftwork_employee')
            ->join('shiftwork', 'shiftwork.id', '=', 'shiftwork_employee.shiftwork_id')
            ->whereBetween(DB::raw('DATE(shiftwork_employee.date)'), array($min, $max))
            ->where('shiftwork_employee.uid', $uid)
            ->orderBy('shiftwork_employee.date', 'ASC')
            ->get();
    }

    public function filterOverTime($min, $max, $uid)
    {
        return $filterPresence = DB::table('overtime')->join('employee', 'employee.uid', '=', 'overtime.uid')
            ->leftJoin('departemen', 'departemen.department_id', '=', 'employee.department_id')
            ->select('overtime.*', 'employee.name', 'departemen.name as department')->whereBetween(DB::raw('DATE(checkIn)'), array($min, $max))->where('overtime.uid', $uid)->orderBy('checkIn', 'DESC')->get();
    }
}
