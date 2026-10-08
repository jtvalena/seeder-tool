<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminInboundOutboundItems extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'admin_inbound_outbound_id',
        'transact_item_id',
        'admin_fas_purchase_requisition_item_id',
        'in_qty'
    ];
}
