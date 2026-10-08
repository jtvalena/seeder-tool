<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeGovernmentDeduction extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'deduction_type_id',
        'payroll_period_detail_id',
        'amount',
    ];

    protected $appends = [
        'type_name',
        'payroll_period_date'
    ];

    public function getTypeNameAttribute(){
        $type_name = DeductionTypes::find($this->deduction_type_id);
        if($type_name){
            return $type_name->name;
        }
        return '';
    }

    public function getPayrollPeriodDateAttribute(){
        $payroll = PayrollPeriodDetail::find($this->payroll_period_detail_id);
        if($payroll){
            return $payroll->payroll_period_date;
        }
        return '';
    }
}
