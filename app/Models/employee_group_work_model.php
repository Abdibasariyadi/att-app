<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employee_group_work_model extends Model
{
    use HasFactory;

    protected $table = 'employee_group_work';

    protected $fillable = [
        'id',
        'date',
        'shiftwork_id',
        'teamwork_id'
    ];
}
