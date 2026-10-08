<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentVoucherBillItems extends Model
{
    use HasFactory;
    protected $fillable = ['payment_voucher_id','enter_bill_ids'];
}
