<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminWarehouseItemStock extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'admin_warehouse_item_stocks';
    protected $fillable = [
        'admin_warehouse_id',
        'admin_item_id',
        'check_in',
        'check_out',
        'quantity',
        'accounted',
        'unaccounted',
        'tagged',
        'msq',
        'reserved',
    ];
}
