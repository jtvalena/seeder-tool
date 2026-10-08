<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class BankBalanceMovement extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'bank_id',
        'in_amount',
        'out_amount',
        'balance_before',
        'balance_after',
        'transact_date',
    ];
}
