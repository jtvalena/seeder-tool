<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeScheduleDetailsTemp extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_schedule_temp_id',
        'time_in',
        'time_out',
        'hours_to_work_day',
        'day',
        'isRestDay',
        'remarks',
        'date',
    ];

    protected $casts = [
        'time_in' => 'date:H:i',
        'time_out' => 'date:H:i',
    ];
}
