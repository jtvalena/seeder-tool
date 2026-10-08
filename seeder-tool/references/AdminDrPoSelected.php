<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDrPoSelected extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_delivery_receipt_id',
        'admin_purchase_order_id',
    ];
}
