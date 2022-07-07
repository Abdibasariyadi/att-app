<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OvertimeModel extends Model
{
    use HasFactory;
    protected $table = 'overtime';

    protected $fillable = [
        'id',
        'uid',
        'checkIn',
        'checkOut',
        'duration'
    ];

    public function getOvertime()
    {
        return $getOvertime = OvertimeModel::join('employee', 'employee.uid', '=', 'overtime.uid')
            ->leftJoin('departemen', 'departemen.department_id', '=', 'employee.department_id')
            ->select('overtime.*', 'employee.name', 'departemen.name as department')
            ->get();
    }

    public function filterOvertime($min, $max)
    {
        return $filterPresence = DB::table('view_attendancehistory')
        ->whereBetween(DB::raw('DATE(date)'), array($min, $max))->orderBy('date', 'DESC')
        ->get();
    }
}
