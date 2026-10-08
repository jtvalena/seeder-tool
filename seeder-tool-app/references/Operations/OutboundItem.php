<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Operations\Outbound;
use App\Models\Items\Item;
use App\Models\Items\ItemUom;

class OutboundItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['model', 'name', 'item_uom_text', 'item_type_text', 'packing_type_text', 'pending_quantity_received'];

    protected $fillable = [
        'outbound_id',
        'item_id',
        'quantity',
        'uom_id',
        'quantity_entered',
        'packing_type',
        'quantity_master_box',
        'quantity_master_box_conv',
        'quantity_received',
        'remarks',
        'loading_list_item_id',
        'received_type'
    ];

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemUom()
    {
        return $this->belongsTo(ItemUom::class, 'uom_id')->withTrashed();
    }

    public function getModelAttribute(){
        $model = !empty($this->item) ? $this->item->model : '';
        unset($this->item);
        return $model;
    }

    public function getNameAttribute(){
        $name = !empty($this->item) ? $this->item->name : '';
        unset($this->item);
        return $name;
    }

    public function getItemUomTextAttribute(){
        $item_uom_text = "";
        if(!empty($this->uom_id)){
            $item_uom_text = !empty($this->itemUom) ? $this->itemUom->name : '';
        }
        unset($this->itemUom);

        return $item_uom_text;
    }

    public function getItemTypeTextAttribute(){
        $item_type_text = !empty($this->item) ? $this->item->item_type_text : '';
        unset($this->item);
        return $item_type_text;
    }

    public function getPackingTypeTextAttribute()
    {
        $packing_type_text = '';

        switch ($this->packing_type) {
            case 1:
                $packing_type_text = 'UOM';
                break;
            case 2:
                $packing_type_text = 'MB';
                break;
            case 3:
                $packing_type_text = 'SB';
                break;
        }

        return $packing_type_text;
    }

    public function getPendingQuantityReceivedAttribute()
    {
        return $this->quantity - $this->quantity_received;
    }
}
