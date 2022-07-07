<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departemenModel extends Model
{
    use HasFactory;
    protected $table = 'departemen';

    protected $fillable = [
        'department_id',
        'name'
    ];
}
