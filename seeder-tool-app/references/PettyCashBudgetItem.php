<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PettyCashBudgetItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'petty_cash_budget_id',
        'department_id',
        'total_amount',
        'remaining_amount',
        'employee_id',
        'special_fund',
        'is_special_fund',
        'company_id',
        'store_branch_id',
        'released_date',
        'advances_coa',
        'ap_coa',
        'special_fund_coa',
    ];
    public function petty_cash_item_logs()
    {
        return $this->hasMany(PettyCash::class, 'petty_cash_budget_item_id');
    }
    public function petty_cash_budget_item_logs()
    {
        return $this->hasMany(PettyCashBudgetItemLog::class, 'petty_cash_budget_item_id');
    }
    public function get_petty_cash_item_log_recent()
    {
        return $this->hasOne(PettyCashBudgetItemLog::class, 'petty_cash_budget_item_id')->whereIn('status', [1,3]);
    }
    public function petty_cash_budget_item_wallets() {
        return $this->hasMany(PettyCashBudgetItemWallet::class, 'petty_cash_budget_item_id');
    }

    /**
     * Calculate remaining amount filtered by current custodian.
     * This ensures only petty_cashes from the current custodian are counted.
     *
     * @param int|null $walletId Optional wallet ID to filter by
     * @return float
     */
    public function calculateRemainingAmountByCurrentCustodian($walletId = null)
    {
        // Get current custodian's user_id from employee_id
        $currentCustodianUserId = \App\Models\EmployeeData::where('id', $this->employee_id)
            ->value('user_id');
        
        if (!$currentCustodianUserId) {
            // If no custodian found, return the stored remaining_amount
            return $this->remaining_amount ?? $this->total_amount;
        }
        
        // Calculate total used amount from petty_cashes filtered by current custodian
        $usedAmount = \App\Models\PettyCashItem::leftJoin('petty_cashes', 'petty_cash_items.petty_cash_id', '=', 'petty_cashes.id')
            ->where('petty_cash_items.petty_cash_budget_item_id', $this->id)
            ->where('petty_cashes.prepared_by', $currentCustodianUserId)
            ->whereNull('petty_cashes.deleted_at')
            ->whereNotNull('petty_cash_items.actual_expense_amount')
            ->whereIn('petty_cashes.status', [1, 2, 3, 4, 5, 6])
            ->when($walletId, function($query) use ($walletId) {
                return $query->where('petty_cashes.wallet_id', $walletId);
            })
            ->sum('petty_cash_items.actual_expense_amount');
        
        // Remaining amount = total_amount - used_amount
        return $this->total_amount - ($usedAmount ?? 0);
    }

    /**
     * Update remaining_amount based on current custodian.
     *
     * @param int|null $walletId Optional wallet ID to filter by
     * @return void
     */
    public function updateRemainingAmountByCurrentCustodian($walletId = null)
    {
        $this->remaining_amount = $this->calculateRemainingAmountByCurrentCustodian($walletId);
        $this->save();
    }
}