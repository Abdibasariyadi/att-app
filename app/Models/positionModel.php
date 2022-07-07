<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class positionModel extends Model
{
    use HasFactory;
    protected $table = 'position';

    protected $fillable = [
        'position_id',
        'name',
        'salary_position'
    ];
}
