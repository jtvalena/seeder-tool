<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EtbBudgetItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'etb_budget_id', 
        'etb_id', 
        'taf_id', 
        'old_amount', 
        'new_amount', 
        'prepared_by', 
    ];
}