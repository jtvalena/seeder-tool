<?php

namespace App\Models;

use App\Models\EmployeeScheduleType;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeSchedule extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'schedule_type_id',
        'schedule_template_id',
        'effectivity_date_start',
        'effectivity_date_end',
        'hours_to_work_week',
        'days_to_work',
        'break_duration',
        'temporary',
        'flexible_breaktime',
        'fixed',
        'no_saturday',
        'rd_no_late'
    ];

    protected $appends = [
        'schedule_type_name'
    ];

    public function getScheduleTypeNameAttribute(){
        $schedule_type_name = EmployeeScheduleType::where('id', $this->schedule_type_id)->value('name');

        return $schedule_type_name ?? '';
    }

    public function employee_schedule_details(){
        return $this->hasMany(EmployeeScheduleDetails::class,'employee_schedule_id')->orderByRaw("FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')");
    }

    public function employee_temporary_schedule_items(){
        return $this->hasMany(EmployeeTemporaryScheduleItems::class, 'employee_schedule_id', 'id');
    }
}
