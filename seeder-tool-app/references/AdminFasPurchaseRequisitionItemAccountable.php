<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFasPurchaseRequisitionItemAccountable extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_fas_purchase_requisition_item_id',
        'employee_id',
        'department_id',
        'quantity',
        'admin_purchase_order_item_id',
    ];
}
