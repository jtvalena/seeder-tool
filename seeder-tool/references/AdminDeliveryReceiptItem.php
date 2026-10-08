<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminDeliveryReceiptItem extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'admin_delivery_receipt_items';

    protected $guarded= [];

    public function admin_delivery_receipts(){
        return $this->belongsTo(AdminDeliveryReceipt::class,'id');
    }
    
    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $inboundOutbound = AdminInboundOutbound::orderBy('id','desc')->first();
            $inboundOutboundItem = new AdminInboundOutboundItems();
            $inboundOutboundItem->admin_inbound_outbound_id = $inboundOutbound->id;
            $inboundOutboundItem->transact_item_id = $model->id;
            $inboundOutboundItem->out_qty = $model->quantity;
            $inboundOutboundItem->save();
        });
    }
}
