<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DispatchItemServiceDelivery extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'dispatch_item_id',
        'sc_delivery_id',
        'dispatch_id',
        'boxes',
    ];

}
