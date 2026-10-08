<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EtbBudget extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'implemented_at', 
        'remarks', 
        'active', 
        'total_amount', 
        'open_balance', 
        'prepared_by', 
        'updated_by', 
        'status', 
        'deleted_by', 
        'status_by'
    ];
    public function etb_budget_items()
    {
        return $this->hasMany(EtbBudgetItem::class, 'etb_budget_id');
    }
}