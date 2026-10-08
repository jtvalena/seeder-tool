<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ScLocationWarehouse extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'sc_location_id',
        'default_warehouse_id',
        'repair_warehouse_id',
        'reserve_warehouse_id',
        'baklas_warehouse_id',
        'defective_warehouse_id',
        'disposal_warehouse_id',
        'recovery_warehouse_id',
        'service_unit_warehouse_id',
    ];
}
