<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledServicePaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_payment_request_id',
        'cancel_reason',
        'cancel_type',
        'cancelled_by',
    ];
}
