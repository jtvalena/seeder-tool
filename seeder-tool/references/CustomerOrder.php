<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerOrder extends Model
{
    use HasFactory, SoftDeletes;

    public function orderList(){
        return $this->hasMany(CustomerOrderItem::class,'customer_order_id');
    }

}
