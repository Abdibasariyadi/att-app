<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AttendanceModel extends Model
{
    use HasFactory;
    protected $table = 'logsAttendance';

    protected $fillable = [
        'id',
        'uid',
        'time'
    ];

    public function getAttendance()
    {
        return $AttendanceModel = AttendanceModel::join('users', 'users.uid', '=', 'logsattendance.uid')->select(
            'logsAttendance.uid',
            'users.name as name',
            DB::raw('min(time(time)) checkin'),
            DB::raw('max(time(time)) checkout'),
            DB::raw('DATE_FORMAT(logsattendance.time, "%Y-%m-%d") as dateFormat')
        )->groupBy('uid', DB::raw('date(time)'), 'users.name', 'dateFormat')->get();
        // select(DB::raw('DATE_FORMAT(logsattendance.time, "%H:%i:%s") as timeFormat'), 'users.uid', 'users.name', DB::raw('DATE_FORMAT(logsattendance.time, "%Y-%m-%d") as dateFormat'));
    }

    public function getAttendanceHistory($uid)
    {
        return $AttendanceModel = AttendanceModel::join('employee', 'employee.uid', '=', 'logsattendance.uid')
            ->leftJoin('shiftwork_employee', 'shiftwork_employee.uid', '=', 'logsattendance.uid')
            ->leftJoin(
                DB::raw('(SELECT time, DATE_FORMAT(logsattendance.time, "%Y-%m-%d")'),
                'logsattendance.time',
                '=',
                'shiftwork_employee.date'
            )
            ->select(
                'logsAttendance.uid',
                'shiftwork.shiftwork',
                'employee.name as name',
                DB::raw('min(time(time)) checkin'),
                DB::raw('max(time(time)) checkout'),
                DB::raw('DATE_FORMAT(logsattendance.time, "%Y-%m-%d") as dateFormat'),
                'shiftwork.checkIn',
                'shiftwork.checkOut',
            )
            ->where('logsAttendance.uid', $uid)
            ->groupBy(
                'uid',
                DB::raw('date(time)'),
                'employee.name',
                'dateFormat',
                'shiftwork.shiftwork',
                'shiftwork.checkIn',
                'shiftwork.checkOut'
            )->get();
        // select(DB::raw('DATE_FORMAT(logsattendance.time, "%H:%i:%s") as timeFormat'), 'users.uid', 'users.name', DB::raw('DATE_FORMAT(logsattendance.time, "%Y-%m-%d") as dateFormat'));
    }
}
