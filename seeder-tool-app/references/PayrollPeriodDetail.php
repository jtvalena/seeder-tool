<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PayrollPeriodDetail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'payroll_period_id',
        'employee_id',
        'status_id',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'absent_no',
        'days_worked',
        'hourly_rate',
        'total_hours_worked',
        'total_unworked_day',
        'total_late_in_hours',
        'late_amount',
        'adjustment_amount',
        'allowance_amount',
        'total_minutes_tardiness',
        'gross_amount',
        'net_amount',
        'unworked_amount',
        'deduction_amount',
        'loans_payables_amount',
        'od',
        'od_amount',
        'odot',
        'odot_amount',
        'odns',
        'odns_amount',
        'odnsot',
        'odnsot_amount',
        'snwd',
        'snwd_amount',
        'snwns',
        'snwns_amount',
        'snwot',
        'snwot_amount',
        'snwnsot',
        'snwnsot_amount',
        'rd',
        'rd_amount',
        'rdns',
        'rdns_amount',
        'rdot',
        'rdot_amount',
        'rdnsot',
        'rdnsot_amount',
        'rh',
        'rh_amount',
        'rhns',
        'rhns_amount',
        'rhot',
        'rhot_amount',
        'rhnsot',
        'rhnsot_amount',
        'rhrd',
        'rhrd_amount',
        'rhrdns',
        'rhrdns_amount',
        'rhrdot',
        'rhrdot_amount',
        'rhrdnsot',
        'rhrdnsot_amount',
        'snwrd',
        'snwrd_amount',
        'snwrdns',
        'snwrdns_amount',
        'snwrdot',
        'snwrdot_amount',
        'snwrdnsot',
        'snwrdnsot_amount',
        'total_od',
        'total_odot',
        'total_odns',
        'total_odnsot',
        'total_snwd',
        'total_snwns',
        'total_snwot',
        'total_snwnsot',
        'total_rd',
        'total_rdns',
        'total_rdot',
        'total_rdnsot',
        'total_rh',
        'total_rhns',
        'total_rhot',
        'total_rhnsot',
        'total_rhrd',
        'total_rhrdns',
        'total_rhrdot',
        'total_rhrdnsot',
        'total_snwrd',
        'total_snwrdns',
        'total_snwrdot',
        'total_snwrdnsot',
        'total_sun',
        'total_sunot',
        'total_sunnd',
        'total_sunndot',
        'for_final_pay',
        'payslip_sent',
        'payslip_received',
        'payslip_received_date',
        'payslip_confirmation_token',
        'for_adjustment',
        'old_ppd_id',
        'total_worked_days',
        'total_unworked_days',
        'total_leave_day_with_pay',
        'total_worked_adjustment',
        'ppd_total_unworked_days',
        'ppd_total_leave_day_with_pay',
        'ppd_total_worked_days',
        'ppd_total_worked_adjustment',
        'timekeep_locked',
        'timekeep_locked_by',
        'timekeep_locked_at',
        'payroll_locked',
        'payroll_locked_by',
        'payroll_locked_at',
    ];

    protected $appends = [
        'employee_id_display',
        'payroll_period_date',
        'employee_full_name',
        'employee_first_name',
        'employee_middle_name',
        'employee_last_name',
        'employee_employment_status',
        'employee_company',
        'employee_department',
        'employee_store_branch',
        'employee_position',
    ];

    protected $employeeDataCache = null;

    public function payroll_period(){
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function timekeeping_record_details()
    {
        return $this->hasMany(TimekeepingRecordDetail::class, 'payroll_period_detail_id', 'id');
    }


    public function getEmployeeIdDisplayAttribute(){
        $id = EmployeeData::find($this->employee_id)->employee_id;
        return $id ? $id : null;
    }

    public function getPayrollPeriodDateAttribute(){
        $payroll = PayrollPeriod::find($this->payroll_period_id);
        if($payroll){
            return $payroll->date_from . ' - ' . $payroll->date_to;
        }
        return '';
    }

    public function getEmployeeData()
    {
        if ($this->employeeDataCache === null && $this->employee_id) {
            $this->employeeDataCache = EmployeeData::find($this->employee_id);
        }

        return $this->employeeDataCache;
    }

    public function getEmployeeFullNameAttribute()
    {
        $employee = $this->getEmployeeData();

        if (!$employee) return null;

        $middleInitial = $employee->middle_name ? substr($employee->middle_name, 0, 1) . '.' : '';
        return "{$employee->last_name}, {$employee->first_name} {$middleInitial}";
    }

    public function getEmployeeFirstNameAttribute()
    {
        return $this->getEmployeeData()?->first_name;
    }

    public function getEmployeeMiddleNameAttribute()
    {
        return $this->getEmployeeData()?->middle_name;
    }

    public function getEmployeeLastNameAttribute()
    {
        return $this->getEmployeeData()?->last_name;
    }

    public function getEmployeeEmploymentStatusAttribute()
    {
        return $this->getEmployeeData()?->active ? 'Active' : 'Inactive';
    }

    public function getEmployeeCompanyAttribute()
    {
        return $this->getEmployeeData()?->company_name ?? '';
    }

    public function getEmployeeDepartmentAttribute()
    {
        return $this->getEmployeeData()?->department_name ?? '';

    }
    public function getEmployeeStoreBranchAttribute()
    {
        return $this->getEmployeeData()?->store_branch_name ?? '';
    }

    public function getEmployeePositionAttribute()
    {
        return $this->getEmployeeData()?->position_name ?? '';
    }
}
