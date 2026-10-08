<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CancelledAdminWarehousePreparationList extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cancelled_admin_warehouse_preparation_lists';

    protected $fillable = [
        'admin_warehouse_preparation_list_id',
        'cancelled_by',
        'cancel_reason',
        'cancel_type',
    ];
}
