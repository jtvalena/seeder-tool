<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeTemporaryScheduleItemDetailTemp extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_temporary_schedule_item_temp_id',
        'time_in',
        'time_out',
        'hours_to_work_day',
        'date',
        'isRestDay',
        'remarks',
    ];

    protected $casts = [
        'time_in' => 'date:H:i',
        'time_out' => 'date:H:i',
    ];

    protected $appends = [
        'day'
    ];

    public function getDayAttribute(){
        $day = Carbon::parse($this->date)->englishDayOfWeek ?? '';
        return $day;
    }
}
