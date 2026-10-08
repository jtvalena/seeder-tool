<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WarehouseLocationBreakdown extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'warehouse_location_id',
        'warehouse_location_type_id',
        'code',
    ];

    public function warehouse_location_type()
    {
        return $this->belongsTo(WarehouseLocationType::class);
    }

    public function warehouse_location()
    {
        return $this->belongsTo(WarehouseLocation::class);
    }
}
