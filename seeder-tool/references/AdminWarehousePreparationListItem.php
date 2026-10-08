<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminWarehousePreparationListItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =[
        'admin_warehouse_preparation_list_id',
        'admin_assets_list_id',
        'status_id',
        'asset_code',
        'serial_number',
        'placed',
    ];

    public function admin_wpf(){
        return $this->belongsTo(AdminWarehousePreparationList::class,'id');
    }
}
