<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkCalendarModel extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'workcalendar';

    protected $fillable = [
        'date',
        'description'
    ];
}
