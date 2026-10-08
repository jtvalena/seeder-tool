<?php

namespace App\Models;

use App\Traits\TimekeepingTrait;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeOverTimeRecordItems extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use TimekeepingTrait;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'employee_overtime_record_items';

    protected $fillable = [
        'id',
        'employee_overtime_record_id',
        'employee_id',
        'ot_time_in',
        'ot_time_out',
        'ot_minutes',
        'ot_reason',
        'reject_reason',
        'status',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'final_confirmed_by',
        'final_confirmed_date',
        'deleted_at',
        'created_at',
        'updated_at',
        'regular_rate_minutes',
        'ot_rate_minutes',
        'payroll_period_id',
        'nd_rate_minutes'
    ];

    protected $appends = [
        'ot_date',
        'gross_ot_minutes',
        'late_in_hours',
        'late_in_minutes',
        'history_count',
        'with_final_approver',
        'has_container',
        'remarks',
        'ot_rate_hours',
        'nd_rate_hours',
        'regular_rate_hours',
        'employee_schedule',
        'series',
        'timekeeping_time_in',
        'timekeeping_time_out',
        'for_emergency',
    ];

    protected $casts = [
        'ot_time_in' => 'date:H:i',
        'ot_time_out' => 'date:H:i',
    ];


    public function EmployeeOvertimeRecord()
    {
        return $this->belongsTo(EmployeeOvertimeRecord::class);
    }

    public function employee_id()
    {
        return $this->hasMany(EmployeeData::class, 'real_employee_id');
    }

    public function employee_overtime_record_item_history(){
        return $this->hasMany(EmployeeOvertimeRecordItemHistory::class, 'employee_overtime_record_item_id', 'id');
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

    public function getGrossOtMinutesAttribute(){
        $ot_in_minutes = $this->ot_hours * 60;

        return $ot_in_minutes;
    }

    public function getOtDateAttribute(){
        return EmployeeOvertimeRecord::getQuery()->find($this->employee_overtime_record_id)->ot_date ?? '';
    }

    public function getLateInHoursAttribute(){
        $late_in_hours = TimekeepingRecordDetail::getQuery()
            ->join('payroll_period_details', 'payroll_period_details.id', 'timekeeping_record_details.payroll_period_detail_id')
            ->where('employee_id', $this->employee_id)
            ->whereDate(DB::raw('date_format(timekeeping_record_details.date_punched,"%Y-%m-%d")'), $this->ot_date)
            ->value('late_in_hours');

        return $late_in_hours ?? 0;
    }

    public function getLateInMinutesAttribute() {
        $late_in_hours = TimekeepingRecordDetail::getQuery()
            ->join('payroll_period_details', 'payroll_period_details.id', 'timekeeping_record_details.payroll_period_detail_id')
            ->where('employee_id', $this->employee_id)
            ->whereDate(DB::raw('DATE_FORMAT(timekeeping_record_details.date_punched, "%Y-%m-%d")'), $this->ot_date)
            ->value('late_in_hours');

        return round(($late_in_hours * 60), 2) ?? 0;
    }

    public function getTimekeepingTimeInAttribute(){
        $time_in = TimekeepingRecordDetail::getQuery()
            ->join('payroll_period_details', 'payroll_period_details.id', 'timekeeping_record_details.payroll_period_detail_id')
            ->where('employee_id', $this->employee_id)
            ->whereDate(DB::raw('date_format(timekeeping_record_details.date_punched,"%Y-%m-%d")'), $this->ot_date)
            ->value('time_in');


        return !is_null($time_in) ? Carbon::parse($time_in)->format('H:i') : '';
    }
    public function getTimekeepingTimeOutAttribute(){
        $time_out = TimekeepingRecordDetail::getQuery()
            ->join('payroll_period_details', 'payroll_period_details.id', 'timekeeping_record_details.payroll_period_detail_id')
            ->where('employee_id', $this->employee_id)
            ->whereDate(DB::raw('date_format(timekeeping_record_details.date_punched,"%Y-%m-%d")'), $this->ot_date)
            ->value('actual_time_out');

        return !is_null($time_out) ? Carbon::parse($time_out)->format('H:i') : '';
    }
    public function getHistoryCountAttribute(){
        $count = EmployeeOvertimeRecordItemHistory::getQuery()
            ->where('employee_overtime_record_item_id', $this->id)
            ->count();

        return $count;
    }

    public function getWithFinalApproverAttribute(){
        $withFinal = EmployeeOvertimeRecordItemHistory::where('employee_overtime_record_item_id', $this->id)
            ->where('status', 3)
            ->exists();

        return $withFinal;
    }

    public function getHasContainerAttribute(){
        $date = $this->ot_date;
        $time_started = Carbon::parse($this->ot_time_in)->format('H:i');
        $time_ended = Carbon::parse($this->ot_time_out)->format('H:i');
        $employee_id = $this->employee_id;

        if (!empty($date)) {
            $container_allowances = ContainerAllowance::getQuery()
                ->select(
                    'container_allowances.id',
                    'container_allowances.time_started',
                    'container_allowances.time_ended',
                )
                ->join('container_allowance_details', 'container_allowances.id', 'container_allowance_details.container_allowance_id')
                ->where('employee_id', $employee_id)
                ->where('container_allowances.container_date', $date)
                ->where('container_allowances.status_id', 3)
                ->orderBy('id', 'desc')
                ->first();

            if($container_allowances){
                $period = CarbonPeriod::create("$date $time_started", "$date $time_ended");
                $start = Carbon::create("$date $container_allowances->time_started");
                $end = Carbon::create("$date $container_allowances->time_ended");

                if ($period->overlaps($start, $end)) {
                    return 1;
                }
            }else{
                return 0;
            }
        } else{
            return 0;
        }
    }

    public static function boot()
    {
        parent::boot();
        // static::creating(function ($model) {
        //     // $latest = SalesQuotation::all()->whereRaw('Right(quoteNum,2) <> "dm"')->last();
        //     $current_payroll_period_id = PayrollPeriod::where('current_active', 1)->value('id');
        //     $model->payroll_period_id = $current_payroll_period_id;
        // });
    }

    public function getRemarksAttribute(){
        return EmployeeOvertimeRecord::getQuery()->find($this->employee_overtime_record_id)->remarks ?? '';
    }

    public function getOtRateHoursAttribute(){
        return number_format($this->ot_rate_minutes / 60, '2', '.');
    }

    public function getNdRateHoursAttribute(){
        return number_format($this->nd_rate_minutes / 60, '2', '.');
    }

    public function getRegularRateHoursAttribute(){
        return number_format($this->regular_rate_minutes / 60, '2', '.');
    }

    public function getEmployeeScheduleAttribute(){
        $emp_details = EmployeeData::find($this->employee_id)->setAppends([
            'employee_full_name',
        ]);
        $employeeSchedule = EmployeeSchedule::where('employee_id', $this->employee_id);
        if($employeeSchedule->first()){
            $schedule = $employeeSchedule->with(['employee_schedule_details','employee_temporary_schedule_items'])->first();
            $schedule->employee_schedule_id = $schedule->id;
            $schedule->schedule_type = $schedule->schedule_type_id;
            $schedule->schedule_type_name = $schedule->schedule_type_name;
            $schedule->employee_schedule_details = $schedule->employee_schedule_details;
            $schedule->break_duration = $schedule->break_duration;
            $schedule->temporary = $schedule->temporary;
        }else{
            $departmentSchedule = WorkScheduleItems::getQuery()->where('department_id', $emp_details->department_id);
            if($departmentSchedule->where('job_title_id', $emp_details->job_title_id)->exists()){
                $schedule = WorkScheduleItems::getQuery()->where('department_id', $emp_details->department_id)->where('job_title_id', $emp_details->job_title_id)->first();
            }else{
                $schedule = WorkScheduleItems::getQuery()->where('department_id', $emp_details->department_id)->first();
            }
            $day_schedule = WorkScheduleTemplate::with(['work_schedule_break_durations','work_schedule_template_details'=> function($q){
                return $q->where('isRestDay', 0);
            }])
            ->select(
                'work_schedule_templates.id',
                'work_schedule_templates.schedule_type_id',
                'work_schedule_templates.schedule_type_id as schedule_type',
                'employee_schedule_types.name as schedule_type_name',
                'work_schedule_templates.break_duration as break_duration',
                'work_schedule_templates.name',
            )
            ->leftJoin('employee_schedule_types', 'employee_schedule_types.id', 'work_schedule_templates.schedule_type_id')
            ->where('work_schedule_templates.id', $schedule->work_schedule_template_id)
            ->first();

            $schedule->schedule_type = $day_schedule->schedule_type_id;
            $schedule->schedule_type_name = $day_schedule->schedule_type_name;
            $schedule->employee_schedule_details = $day_schedule->work_schedule_template_details;
            $schedule->work_schedule_break_durations = $day_schedule->work_schedule_break_durations;
            $schedule->break_duration = $day_schedule->break_duration;
        }
        return $schedule;

    }

    public function getSeriesAttribute(){
        $data = EmployeeOvertimeRecord::getQuery()->find($this->employee_overtime_record_id);
        return $data->series ?? '';
    }

    public function getForEmergencyAttribute(){
        return $this->checkIfDateIsNotWithinCurrentPayroll($this->ot_date);
    }
}
