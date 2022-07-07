<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryModel extends Model
{
    use HasFactory;
    protected $table = 'salary';

    protected $fillable = [
        'id',
        'uid',
        'period'
    ];
}
