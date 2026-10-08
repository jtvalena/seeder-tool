<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledCurrencyPurchase extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'currency_purchase_id',
        'cancel_reason',
        'cancel_type',
        'cancelled_by',
    ];
}
