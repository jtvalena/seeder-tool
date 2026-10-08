<?php
namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\Item;

class WarehouseTransferItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['stock', 'model', 'name', 'source_item_stock', 'destination_item_stock', 'uom_text'];

    protected $fillable = [
        'warehouse_transfer_id',
        'item_id',
        'requested_quantity',
        'quantity',
    ];

    public function warehouse_transfer()
    {
        return $this->belongsTo(WarehouseTransfer::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getStockAttribute(){
        return 0;
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

    public function getUomTextAttribute(){
        $uom_text = !empty($this->item->itemUom) ? $this->item->itemUom->name : '';
        unset($this->item);
        return $uom_text;
    }

    public function getSourceItemStockAttribute(){
        $stock = WarehouseItemStocks::where('warehouse_id', $this->warehouse_transfer->source_id)->where('item_id', $this->item_id)->first();
        return !empty($stock->quantity) ? $stock->quantity : 0;
    }

    public function getDestinationItemStockAttribute(){
        $stock = WarehouseItemStocks::where('warehouse_id', $this->warehouse_transfer->destination_id)->where('item_id', $this->item_id)->first();
        return !empty($stock->quantity) ? $stock->quantity : 0;
    }
}
