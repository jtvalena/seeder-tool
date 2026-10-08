<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashBudgetItemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'replenish_amount',
        'petty_cash_id',
        'petty_cash_item_id',
        'petty_cash_budget_id',
        'petty_cash_budget_item_id',
        'new_amount',
        'old_amount',
        'prepared_by',
        'replenished_date',
        'status',
        'approved_by',
        'old_employee_id',
        'new_employee_id',
    ];

    public function getStatusTextAttribute()
    {
        if($this->status == 1) {
            return 'Budget History';
        } else if($this->status == 2) {
            return 'Transaction Deduction History';
        } else if($this->status == 3) {
            return 'Replenished History';
        } else if($this->status == 4) {
            return 'Reduce Special Fund Amount';
        } else if($this->status == 5) {
            return 'Special Fund Update Amount';
        } else if($this->status == 6) {
            return 'Custodian History';
        }
    }
}