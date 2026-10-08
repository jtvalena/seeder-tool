<?php

namespace App\Models;

use App\Models\Operations\Warehouse;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WarehouseLocation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code',
        'name',
        'warehouse_id',
        'barcode',
        'allow_butal',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function warehouse_location_breakdowns()
    {
        return $this->hasMany(WarehouseLocationBreakdown::class);
    }

    public function warehouse_location_item_defaults()
    {
        return $this->hasMany(WarehouseLocationItemDefault::class);
    }
}
