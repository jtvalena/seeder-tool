<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnHoldPaymentVoucher extends Model
{
    protected $table = 'on_hold_payment_vouchers';

    protected $fillable = [
        'payment_voucher_id',
        'user_id',
        'reason',
        'active',
        'inactive_by',
        'inactive_at',
    ];
}
