<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledRecurringBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'recurring_bill_id',
        'cancel_reason',
        'cancel_type',
        'cancelled_by',
    ];
}
