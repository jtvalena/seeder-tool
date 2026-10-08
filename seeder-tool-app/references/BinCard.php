<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Operations\Warehouse;
use App\Models\Operations\TransactType;
use App\Models\Items\Item;
use App\Models\User;

class BinCard extends Model
{
    use SoftDeletes;

    protected $table = 'bincard';

    protected $appends = ['warehouse_name'];

    protected $fillable = [
        'transact_type_id',
        'transact_id',
        'item_id',
        'in_qty',
        'out_qty',
        'warehouse_id',
        'user_id',
        'transact_date',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function transact_type()
    {
        return $this->belongsTo(TransactType::class, 'transact_type_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getWarehouseNameAttribute(){
        $warehouse_name = !empty($this->warehouse) ? $this->warehouse->name : '';
        unset($this->warehouse);
        return $warehouse_name;
    }
}
