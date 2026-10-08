<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WarehouseReturnCancelItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'warehouse_return_item_id',
        'quantity',
        'user_id',
        'date_done'
    ];
}
