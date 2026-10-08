<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTemporarySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'time_in',
        'time_out',
        'date_start',
        'date_end',
    ];
}
