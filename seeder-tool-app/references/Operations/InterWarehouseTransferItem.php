<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class InterWarehouseTransferItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'inter_warehouse_transfer_items';

    protected $fillable = [
        'iw_id',
        'item_id',
        'quantity',
        'request_type',
        'allow_butal',
        'm_box_conversion',
        'remarks',
    ];
}

