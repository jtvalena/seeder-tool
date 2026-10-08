<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledInternationalPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'international_payment_id',
        'cancel_reason',
        'cancel_type',
        'cancelled_by',
    ];
}
