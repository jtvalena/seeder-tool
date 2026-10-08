<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeTemporaryScheduleItemsTemp extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_schedule_temp_id',
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

    public function employee_temporary_schedule_item_details(){
        return $this->hasMany(EmployeeTemporaryScheduleItemDetailTemp::class, 'employee_temporary_schedule_item_temp_id', 'id')->orderBy('date', 'asc');
    }

    public function getEffectivityDateAttribute(){
        if($this->effectivity_date_start && $this->effectivity_date_end){
            return [ Carbon::parse($this->effectivity_date_start)->format('Y-m-d'), Carbon::parse($this->effectivity_date_end)->format('Y-m-d') ];
        }
        return [];
    }
}
