<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CancelledRestDayRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rest_day_request_id',
        'reason',
        'cancelled_date',
        'cancelled_by',
        'old_date_from',
        'new_date_from',
        'old_cancelled_rd_date',
        'new_cancelled_rd_date',
    ];
}


