<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeChangeScheduleItem extends Model
{
    use HasFactory;

    protected $table = 'employee_change_schedule_items';

    protected $fillable = [
        'employee_change_schedule_id',
        'day',
        'time_in',
        'time_out',
        'isRestDay',
        'no_break',
    ];

    public function schedule()
    {
        return $this->belongsTo(EmployeeChangeSchedule::class, 'employee_change_schedule_id');
    }
}
