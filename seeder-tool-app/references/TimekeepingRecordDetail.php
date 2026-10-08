<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimekeepingRecordDetail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'payroll_period_detail_id',
        'approved_by',
        'approved_date',
        'manual',
        'mobile',
        'time_in',
        'time_out',
        'late_in_hours',
        'hours_worked',
        'reason',
        'latitude_in',
        'longitude_in',
        'latitude_out',
        'longitude_out',
        'isRestDay',
        'absent',
        'total_break_duration',
        'day_worked',
        'actual_time_out',
        'actual_time_in',
        'excess_hours',
        'rejected',
        'ob_record_item_id',
        'origin',
        'eajf_item_id',
        'leave_request_id',
        'offset',
        'eight_hour_reached_at',
        'unworked_day',
        'payroll_period_detail_id',
        'date_punched',
        'location',
        'leave_days_with_pay',
        'waive_late_for_holiday_and_late',
    ];

    protected $appends = [
        'day',
        'pending_leave',
        'pending_ot',
        'pending_ob',
        'uploadedFile',
        'editActive',
        'week_no',
        'all_locations'
    ];

    public function timekeeping_record_detail_file(){
        return $this->hasMany(TimekeepingRecordDetailFile::class, 'timekeeping_record_detail_id');
    }

    public function timekeeping_record_detail_reason(){
        return $this->hasOne(TimekeepingRecordDetailReason::class, 'timekeeping_record_detail_id');
    }

    public function payroll_period_detail()
    {
        return $this->belongsTo(PayrollPeriodDetail::class);
    }

    public function payroll_period()
    {
        return $this->hasManyThrough(
            PayrollPeriod::class,
            PayrollPeriodDetail::class,
            'payroll_period_id',
            'id',
            'payroll_period_detail_id',
            'id'
        );
    }

    public function employee_timekeeping_record_break_durations(){
        return $this->hasMany(EmployeeTimekeepingRecordBreakDurations::class, 'timekeeping_record_detail_id', 'id');
    }

    public function getPendingLeaveAttribute(){
        $current_payroll_period_id = PayrollPeriod::where('current_active', 1)->value('id');
        $record = LeaveRequest::where('employee_id', $this->employee_id)
            ->where('payroll_period_id', $current_payroll_period_id)
            ->where('status', 1)
            ->get();
        return $record;
    }

    public function getPendingOtAttribute(){
        $current_payroll_period_id = PayrollPeriod::where('current_active', 1)->value('id');
        $record = EmployeeOvertimeRecordItems::where('employee_id', $this->employee_id)
            ->where('payroll_period_id', $current_payroll_period_id)
            ->where('status', 1)
            ->get();
        return $record;
    }

    public function getPendingObAttribute(){
        $current_payroll_period_id = PayrollPeriod::where('current_active', 1)->value('id');
        $record = EmployeeOBRecord::where('employee_id', $this->employee_id)
            ->where('payroll_period_id', $current_payroll_period_id)
            ->where('status', 1)
            ->get();
        return $record;
    }

    public function getUploadedFileAttribute(){
        return TimekeepingRecordDetailFile::getQuery()->where('timekeeping_record_detail_id', $this->id)->first();
    }

    public function getEditActiveAttribute(){
        return false;
    }

    public function getDayAttribute(){
        return Carbon::parse((string)$this->date_punched)->englishDayOfWeek ?? 0;
    }

    public function getWeekNoAttribute(){
        return Carbon::parse($this->date_punched)->weekOfYear;
    }

    public function employee_data(){
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }

    public function timekeeping_hour_details(){
        return $this->hasMany(TimekeepingHourDetails::class, 'timekeeping_record_detail_id');
    }

    public function ob_record_items(){
        return $this->belongsTo(EmployeeOBRecordItems::class, 'ob_record_item_id', 'id');
    }

    public function employee_attendance_justification_items(){
        return $this->belongsTo(EmployeeAttendanceJustificationItem::class, 'eajf_item_id', 'id');
    }

    public function getAllLocationsAttribute(){
        $locations = array();
        if($this->location){
            $locations[] = $this->location;
        }
        $employee = $this->employee_data;

        $ob = $this->ob_record_items;
        $ob_location = '';
        if($ob){
            $ob_branch_id_basis = $ob->store_branch_id ? $ob->store_branch_id : $employee->store_branch_id;
            $ob_location = StoreBranch::where('id', $ob_branch_id_basis)->value('name');
            $locations[] = $ob_location;
        }
        $eaj = $this->employee_attendance_justification_items;
        $eaj_location = '';
        if($eaj){
            $eaj_branch_id_basis = $eaj->store_branch_id ? $eaj->store_branch_id : $employee->store_branch_id;
            $eaj_location = StoreBranch::where('id', $eaj_branch_id_basis)->value('name');
            $locations[] = $eaj_location;
        }

        return  implode(", ", $locations);
    }

    public function getStatusTextAttribute()
    {
        $status_text = '';

        switch ($this->status) {
            case 1:
                $status_text = 'saved';
                break;
            case 2:
                $status_text = 'rejected';
                break;
            case 3:
                $status_text = 'approved';
                break;
            case 4:
                $status_text = 'confirmed';
                break;
            case 5:
                $status_text = 'modified';
                break;
            default:
                $status_text = '';
        }

        return $status_text;
    }

    public function origin_text(){
        // switch($this->origin){  
        //     case 1:
        //         $origin_text = 'biometrics';
        //         break;
        //     case 2:
        //         $origin_text = 'manual';
        //         break;
        //     case 3:
        //         $origin_text = 'ob-form';
        //         break;
        //     case 4:
        //         $origin_text = 'taf';
        //         break;
        //     case 5:
        //         $origin_text = 'EAJF';
        //         break;
        //     default:
        //         $origin_text = '';
        //         break;
        // }
    }
}
