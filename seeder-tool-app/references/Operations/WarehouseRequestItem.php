<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\Item;

class WarehouseRequestItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['stock'];

    protected $fillable = [
        'warehouse_request_id',
        'item_id',
        'quantity',
    ];

    public function warehouse_request()
    {
        return $this->belongsTo(WarehouseRequest::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getStockAttribute(){
        return 0;
    }
}
