<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineSetModel extends Model
{
    use HasFactory;
    protected $table = 'machineset';

    protected $fillable = [
        'id',
        'name',
        'ipAddress',
        'port'
    ];
}
