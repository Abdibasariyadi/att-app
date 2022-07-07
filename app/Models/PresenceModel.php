<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PresenceModel extends Model
{
    use HasFactory;
    protected $table = 'presence';

    protected $fillable = [
        'uid',
        'checkIn',
        'checkOut',
        'attendanceStatusCode'
    ];

    public function getPresence()
    {
        return $getPresence = PresenceModel::leftJoin('employee', 'employee.uid', '=', 'presence.uid')
            ->join('attendancestatus', 'attendancestatus.attendanceStatusCode', '=', 'presence.attendanceStatusCode')
            ->join('departemen', 'departemen.department_id', '=', 'employee.department_id')
            ->select('presence.id', 'presence.uid', 'checkIn', 'checkOut', 'employee.name as name', 'attendancestatus.attendanceStatus as attendanceStatus', 'departemen.name as departmentName')
            ->orderBy('employee.id', 'DESC')->get();
    }

    public function filterPresence($min, $max)
    {
        return $filterPresence = DB::table('view_attendancehistory')->whereBetween(DB::raw('DATE(date)'), array($min, $max))->orderBy('date', 'DESC')->get();
    }
}
