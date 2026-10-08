<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminReleaseFormItem extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'admin_release_form_items';
    protected $fillable = [
        'admin_release_form_id',
        'item_name',
        'admin_item_id',
        'ref_doc',
        'admin_fas_purchase_requisition_item_id',
    ];
    
    private $transientAttributes = [];

    public function setTransientAttribute($key, $value)
    {
        $this->transientAttributes[$key] = $value;
    }

    public function getTransientAttribute($key)
    {
        return $this->transientAttributes[$key] ?? null;
    }

    public static function boot()
    {
       
        parent::boot();
        static::created(function ($model) {
            $inbound_outbound_type_id = request()->get('inbound_outbound_type_id');
            $findInboundType = InboundOutboundType::where('id',$inbound_outbound_type_id)->first();
            if($findInboundType->module != 'AdminFasBasketOut'){
                $inboundOutbound = AdminInboundOutbound::orderBy('id','desc')->first();
                $inboundOutboundItem = new AdminInboundOutboundItems();
                $inboundOutboundItem->admin_inbound_outbound_id = $inboundOutbound->id;
                $inboundOutboundItem->transact_item_id = $model->id;
                $inboundOutboundItem->admin_fas_purchase_requisition_item_id = $model->admin_fas_purchase_requisition_item_id ? $model->admin_fas_purchase_requisition_item_id : null;
                $inboundOutboundItem->out_qty = $model->getTransientAttribute('out_qty');
                $inboundOutboundItem->save();
            }
        });
    }
}
