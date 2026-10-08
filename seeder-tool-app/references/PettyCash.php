<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PettyCash extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'implemented_at',
        'remarks',
        'status',
        'prepared_by',
        'updated_by',
        'transact_type_id',
        'transact_id',
        'department_id',
        'total_amount',
        'petty_cash_budget_id',
        'petty_cash_budget_item_id',
        'compiled',
        'last_remaining_amount',
        'remaining_amount',
        'total_budget',
        'employee_id',
        'pcf_number',
        'dept_approved_by',
        'ap_approved_by',
        'acct_approved_by',
        'special_fund',
        'selected_by_petty_cash_id',
        'pending_remaining_budget',
        'unliquidated_amount',
        'total_released_amount',
        'total_actual_expense_amount',
        'total_returned_amount',
        'date_covered_from',
        'date_covered_to',
        'cash_denomination',
        'paid_amount',
        'wallet_id',
        'date_established',
        'acct_approved_date',
        'dept_approved_date',
        'dept_submission_date',
        'deleted_by',
        'reason',
    ];

    public function petty_cash_items()
    {
        return $this->hasMany(PettyCashItem::class, 'petty_cash_id')
            ->orderByRaw('(sequence IS NULL) ASC')
            ->orderBy('sequence')
            ->orderBy('id');
    }
    public function cancelled_petty_cash_lists()
    {
        return $this->hasMany(CancelledPettyCash::class, 'petty_cash_id');
    }
    public function tableNameForUpload()
    {
        return 'PaymentBillUpload';
    }
    public function fundCustodian()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function immediateSuperior()
    {
        return $this->belongsTo(User::class, 'dept_approved_by');
    }

    public function ApprovedByAccounting()
    {
        return $this->belongsTo(User::class, 'acct_approved_by');
    }
}