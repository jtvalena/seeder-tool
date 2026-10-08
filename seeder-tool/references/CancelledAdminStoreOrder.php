<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;


class CancelledAdminStoreOrder extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'admin_store_order_id',
        'reason',
        'cancel_type',
        'cancelled_by',
        'cancelled_date', 
        'action',
        'revert_date', 
        'revert_by', 
        'reset_date', 
        'reset_by', 
        'deleted_at',
    ];
}
