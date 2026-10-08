<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CashAdvance extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'doc_num',
        'employee_id',
        'total_amount',
        'payable_in',
        'start_deduction_date',
        'deduction_amount',
        'company_id',
        'store_branch_id',
        'department_id',
        'status'
    ];

    protected $appends = [
        'paid_amount',
        'remaining_amount',
        'payroll_start_date'
    ];

    public function cash_advance_items()
    {
        return $this->hasMany(CashAdvanceItem::class, 'cash_advance_id')->orderBy('payable_in_date');
    }

    public function getPaidAmountAttribute(){
        $paid_amount = 0;
        $cash_advance_items = $this->cash_advance_items->where('paid', 1)->where('skipped', 0);
        foreach ($cash_advance_items as $item) {
            $paid_amount += $item->amount;
        }
        return $paid_amount;
    }

    public function getRemainingAmountAttribute(){
        $remaining_amount = 0;
        $remaining_amount = $this->total_amount - $this->paid_amount;
        return $remaining_amount;
    }

    public function getPayrollStartDateAttribute(){
        $payroll_period = PayrollPeriod::find($this->payroll_period_id);
        return $payroll_period ? $payroll_period->date_from : null;
    }

    // 1 = "PENDING"
    // 2 = "FOR DEPT"
    // 3 = "FOR HR"
    // 4 = "PROCESSING"
    // 5 = "FOR RELEASING"
    // 6 = "ON GOING"
    // 7 = "COMPLETED"
}
