<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogisticBasketWpf extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function logisticBasket()
    {
        return $this->belongsTo(LogisticBasket::class);
    }

    public function warehousePreparationList()
    {
        return $this->belongsTo(\App\Models\Operations\WarehousePreparationList::class, 'wpf_id');
    }

    public function wpf()
    {
        return $this->belongsTo(\App\Models\Operations\WarehousePreparationList::class, 'wpf_id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(\App\Models\Sales\SalesOrder::class, 'so_id');
    }
}
