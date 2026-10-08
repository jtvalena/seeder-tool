<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WarehouseLocationType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code',
        'name',
        'prefix',
        'suffix',
        'parent_id',
    ];

    public function warehouse_location_breakdowns()
    {
        return $this->hasMany(WarehouseLocationBreakdown::class);
    }
}
