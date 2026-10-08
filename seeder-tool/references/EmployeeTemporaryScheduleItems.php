<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeTemporaryScheduleItems extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
        'employee_schedule_id',
        'effectivity_date_start',
        'effectivity_date_end',
        'days_to_work',
        'hours_to_work_week',
        'break_duration',
        'restday',
    ];

    protected $appends = [
        'effectivity_date'
    ];

    public function employee_temporary_schedule_item_details()
    {
        return $this->hasMany(EmployeeTemporaryScheduleItemDetails::class, 'employee_temporary_schedule_item_id');
    }

    public function getEffectivityDateAttribute(){
        if($this->effectivity_date_start && $this->effectivity_date_end){
            return [ Carbon::parse($this->effectivity_date_start)->format('Y-m-d'), Carbon::parse($this->effectivity_date_end)->format('Y-m-d') ];
        }
        return [];
    }
}
