<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponentModel extends Model
{
    use HasFactory;

    protected $table = 'salary_component';

    protected $fillable = [
        'id',
        'component_code',
        'component_name',
        'type',
        'amount'
    ];
}
