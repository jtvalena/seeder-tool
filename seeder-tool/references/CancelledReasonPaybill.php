<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CancelledReasonPaybill extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'payment_voucher_id',
        'reason',
        'action',
        'user_id', 
        'revert_by', 
        'action_date', 
        'previous_status',
        'new_status',
    ];
}
