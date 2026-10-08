<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PettyCashItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'petty_cash_id',
        'sequence',
        'department_id',
        'released_amount',
        'petty_cash_budget_id',
        'petty_cash_budget_item_id',
        'for_replenish',
        'transact_item_id',
        'particular',
        'date',
        'store_branch_id',
        'coa_id',
        'employee_id',
        'actual_expense_amount',
        'recognition_month',
        'job_id',
        'reference_type', // transact type of paid by paybill
        'reference_number', // id of paybills
        'for_atd_reason',
        'bounced',
        'po_id',
        'jo_id',
        'date_liquidated',
        'reference',
        'supplier',
        'received_by',
        'beginning_odometer',
        'ending_odometer',
    ];

    public function petty_cash_item_files()
    {
        return $this->hasMany(PettyCashItemFile::class, 'petty_cash_item_id');
    }
    public function petty_cash_history()
    {
        return $this->hasMany(PettyCashHistory::class, 'petty_cash_item_id');
    }
}