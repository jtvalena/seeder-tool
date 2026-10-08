<?php

namespace App\Models;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WarehouseLocationItemDefault extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'warehouse_location_id',
        'item_id',
        'quantity',
    ];

    public function warehouse_location()
    {
        return $this->belongsTo(WarehouseLocation::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
