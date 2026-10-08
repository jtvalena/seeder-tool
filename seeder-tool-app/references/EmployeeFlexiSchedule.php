<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeFlexiSchedule extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'employee_flexi_schedules';
    protected $fillable = [
        'series',
        'payroll_period_id',
        'employee_id',
        'status',
        'created_by',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'rejected_by',
        'rejected_date',
        'reject_reason',
        'remarks',
        'break_duration',
    ];

    protected $appends = [
        'status_name',
        'approved_by_name',
        'confirmed_by_name',
        'rejected_by_name',
    ];

    public function employee_data()
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }

    public function payroll_period()
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function employee_flexi_schedule_details()
    {
        return $this->hasMany(EmployeeFlexiScheduleDetail::class, 'employee_flexi_schedule_id');
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            DB::transaction(function () use ($model) {
                $latest = DB::table('employee_flexi_schedules')->lockForUpdate()->max('series');

                if (!empty($latest)) {
                    $next_series = intval(substr($latest, -6)) + 1;
                    $model->series = str_pad((string)$next_series, 6, '0', STR_PAD_LEFT);
                } else {
                    $model->series = str_pad('1', 6, '0', STR_PAD_LEFT);
                }

                $model->save();
            });
        });
    }

    public function getStatusNameAttribute()
    {
        switch($this->status){
            case 1:
                return 'saved';
            case 2:
                return 'approved';
            case 3:
                return 'confirmed';
            case 4:
                return 'rejected';
        }
    }

    public function getApprovedByNameAttribute()
    {
        return $this->approved_by ? User::find($this->approved_by)->name : '';
    }

    public function getConfirmedByNameAttribute()
    {
        return $this->confirmed_by ? User::find($this->confirmed_by)->name : '';
    }

    public function getRejectedByNameAttribute()
    {
        return $this->rejected_by ? User::find($this->rejected_by)->name : '';
    }
}
