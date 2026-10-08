<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderDeliveryDateHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sales_order_delivery_date_history';

    protected $fillable = [
        'sales_order_id',
        'delivery_date',
        'user_id',
    ];
}
