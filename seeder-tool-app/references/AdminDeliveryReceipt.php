<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class AdminDeliveryReceipt extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'admin_delivery_receipts';

    protected $fillable = [
        'reference_number',
        'admin_purchase_order_id',
        'delivery_to',
        'delivery_address',
        'warehouse_id',
        'customer_id',
        'remarks',
    ];

    public function admin_delivery_receipt_items(){
        return $this->hasMany(AdminDeliveryReceiptItem::class,'admin_delivery_receipt_id');
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            
            $data = request()->all();
            
            $id = AdminDeliveryReceipt::orderBy('admin_delivery_receipts.id', 'desc')->pluck('admin_delivery_receipts.id')->first();
            $formatted_reference_num = str_pad($id ? $id + 1 : 1, 4, '0', STR_PAD_LEFT);
            $current_year = Carbon::now()->year;

            $inboundOutbound = new AdminInboundOutbound();
            $inboundOutbound->transaction_number = request()->get('arf_num') ? request()->get('arf_num') : $current_year. '-' . $formatted_reference_num;
            $inboundOutbound->transact_id = $model->id;
            $inboundOutbound->inbound_outbound_type_id = $data['inbound_outbound_type_id'];
            $inboundOutbound->warehouse_id = isset($data['warehouse_id'])?$data['warehouse_id']:0;
            $inboundOutbound->remarks = $data['remarks'] ? $data['remarks']:'';
            $inboundOutbound->customer_id = isset($data['customer_id']) ? $data['customer_id']:'';
            $inboundOutbound->created_by = Auth::user()->id;
            $inboundOutbound->type = 2;
            $inboundOutbound->deliver_to = isset($data['delivery_to']) ? $data['delivery_to'] : null;
            $inboundOutbound->deliver_to_address = isset($data['delivery_address']) ? $data['delivery_address'] : null;
            $inboundOutbound->save();
        });
    }
}
