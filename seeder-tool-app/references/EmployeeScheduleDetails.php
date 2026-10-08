<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeScheduleDetails extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $fillable = [
        'employee_schedule_id',
        'time_in',
        'time_out',
        'hours_to_work_day',
        'day',
        'remarks',
        'isRestDay',
        'date',
    ];

    // protected $casts = [
    //     'time_in' => 'date:H:i',
    //     'time_out' => 'date:H:i',
    // ];
}
