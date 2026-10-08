<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class BankCard extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'card_name',
        'card_number',
        'card_expiration',
        'card_type',
        'bank_id',
        'billing_date',
        'due_date',
        'card_network_id',
        'card_limit',
        'active',
        'company_id',
        'vendor_id',
        'store_branches_id',
        'coa_id,'
    ];
}
