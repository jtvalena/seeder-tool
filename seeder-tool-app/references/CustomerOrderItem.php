<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'customer_order_id',
        'item_id',
        'quantity',
        'srp',
        'disc_price',
        'total_amount',
        'amount',
        'original_item_id',
    ];
}
