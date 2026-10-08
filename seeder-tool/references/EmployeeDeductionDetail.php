<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeDeductionDetail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_deduction_id',
        'deduction_date',
        'deducted',
        'amount',
        'particulars',
        'skipped',
        'payroll_period_detail_id',
        'payroll_period_id',
        'paybill_paid',
    ];

    public function employee_deductions(){
        return $this->belongsTo(EmployeeDeduction::class);
    }
}
