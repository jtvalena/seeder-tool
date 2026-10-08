<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeTemporaryScheduleItemDetails extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
        'employee_temporary_schedule_item_id',
        'time_in',
        'time_out',
        'hours_to_work_day',
        'date',
        'isRestDay',
        'remarks',
    ];

    // protected $casts = [
    //     'time_in' => 'date:H:i',
    //     'time_out' => 'date:H:i',
    // ];

    protected $appends = [
        'day'
    ];

    public function getDayAttribute(){
        $day = Carbon::parse($this->date)->englishDayOfWeek ?? '';
        return $day;
    }
}
