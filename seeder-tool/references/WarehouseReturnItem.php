<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WarehouseReturn;
use OwenIt\Auditing\Contracts\Auditable;

class WarehouseReturnItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'warehouse_return_id',
        'item_id',
        'quantity',
        'deleted_at',
    ];

    public function warehouse_return()
    {
        return $this->belongsTo(WarehouseReturn::class);
    }
}
