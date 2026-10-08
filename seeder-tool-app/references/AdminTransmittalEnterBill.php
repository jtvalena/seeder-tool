<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminTransmittalEnterBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_transmittal_id',
        'admin_transmittal_item_id',
        'admin_enter_bill_id',
    ];
}
