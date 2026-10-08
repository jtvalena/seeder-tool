<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PettyCashBudget extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'implemented_at',
        'remarks',
        'active',
        'total_amount',
        'open_balance',
        'buffer_amount',
        'petty_cash_budget_id',
        'prepared_by',
        'updated_by',
        'status',
        'coa_id',
    ];
    public function petty_cash_budget_items()
    {
        return $this->hasMany(PettyCashBudgetItem::class, 'petty_cash_budget_id');
    }
} 