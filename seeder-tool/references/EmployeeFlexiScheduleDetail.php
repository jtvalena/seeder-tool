<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeFlexiScheduleDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_flexi_schedule_id',
        'timekeeping_record_detail_id',
        'date',
        'time_in',
        'time_out',
        'isRestDay',
        'no_break',
        'store_branch_id',
    ];

    protected $appends = ['day'];

    protected $casts = [
        'time_in' => 'datetime:H:i',
        'time_out' => 'datetime:H:i',
    ];

    public function employee_flexi_schedule()
    {
        return $this->belongsTo(EmployeeFlexiSchedule::class, 'employee_flexi_schedule_id');
    }

    public function timekeeping_record_detail()
    {
        return $this->belongsTo(TimekeepingRecordDetail::class, 'timekeeping_record_detail_id');
    }

    public function getDayAttribute()
    {
        return Carbon::parse($this->date)->format('l');
    }
}
