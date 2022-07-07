<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamWorkModel extends Model
{
    use HasFactory;
    protected $table = 'teamwork';

    protected $fillable = [
        'TeamWorkName',
        'shiftwork _id'
    ];
}
