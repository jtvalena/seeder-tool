<?php

namespace App\Models\Operations;

use App\Models\Service\ScLocation;
use App\Models\WarehouseCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Warehouse extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code',
        'name',
        'active',
        'default',
        'virtual',
        'allow_butal',
        'warehouse_category_id',
        'parent_id',
    ];

    public function warehouse_item_stocks()
    {
        return $this->hasMany(WarehouseItemStocks::class, 'warehouse_id');
    }

    public function itemJournal()
    {
        return $this->hasMany(ItemJournal::class,'warehouse_id');
    }

    public function sc_locations()
    {
        return $this->hasMany(ScLocation::class,'warehouse_id');
    }

    public function warehouse_category()
    {
        return $this->belongsTo(WarehouseCategory::class);
    }

    public function parent()
    {
        return $this->belongsTo(Warehouse::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Warehouse::class, 'parent_id');
    }
}
