<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeScheduleTemp extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'updated_by',
        'employee_id',
        'employee_schedule_id',
        'schedule_template_id',
        'schedule_type_id',
        'temporary',
        'break_duration',
        'effectivity_date_start',
        'effectivity_date_end',
        'hours_to_work_week',
        'days_to_work',
        'action',
        'flexible_breaktime',
        'fixed',
        'no_saturday',
        'rd_no_late',
    ];

    protected $appends = [
        'approved_by_name'
    ];

    protected $casts = [
        'approved_date' => 'date:Y-m-d',
    ];

    public function getApprovedByNameAttribute(){
        if($this->approved_by){
            $user = User::where('id', $this->approved_by)->first();

            return $user->name;
        }
        return '';
    }

    public function employee_schedule_details(){
        return $this->hasMany(EmployeeScheduleDetailsTemp::class, 'employee_schedule_temp_id');
    }

    public function employee_temporary_schedule_items(){
        return $this->hasMany(EmployeeTemporaryScheduleItemsTemp::class, 'employee_schedule_temp_id', 'id');
    }
}
