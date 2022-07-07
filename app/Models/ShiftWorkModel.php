<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftWorkModel extends Model
{
    use HasFactory;
    protected $table = 'shiftwork';

    protected $fillable = [
        'id',
        'shiftwork',
        'checkIn',
        'checkOut'
    ];
}
