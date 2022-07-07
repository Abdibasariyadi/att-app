<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingGroupModel extends Model
{
    use HasFactory;

    protected $table = 'workinggroup';

    protected $fillable = [
        'uid',
        'teamWork_id'
    ];

    public function getWorkingGroup($id)
    {
        return $getWorkingGroup = WorkingGroupModel::leftJoin('employee', 'workinggroup.uid', '=', 'employee.uid')
            ->select('employee.uid as uid', 'name')
            ->where('teamWork_id', '=', $id);
    }
}
