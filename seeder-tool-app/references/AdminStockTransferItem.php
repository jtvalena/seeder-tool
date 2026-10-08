<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminStockTransferItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'admin_stock_transfer_items';
    protected $fillable = [
        'admin_stock_transfer_id',
        'item_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    public function stockTransfer(){
        return $this->belongsTo(AdminStockTransfer::class,'id');
    }
}
