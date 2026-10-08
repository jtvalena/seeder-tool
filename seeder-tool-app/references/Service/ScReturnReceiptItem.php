<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScReturnReceiptItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sc_return_receipt_id',
        'sc_parts_order_item_id',
        'item_id',
        'quantity',
        'justification',
        'decision'
    ];
}
