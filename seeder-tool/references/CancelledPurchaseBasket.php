<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CancelledPurchaseBasket extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'cancelled_purchase_baskets';

    protected $fillable = [
        'admin_fas_basket_id',
        'reason',
        'department_id',
        'action',
        'revert_date',
        'revert_by',
        'cancelled_date',
        'cancelled_by',
        'reset_date',
        'reset_by',
    ];
}
