<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminFasBasketSubItem extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'admin_fas_basket_id',
        'admin_fas_basket_item_id',
        'vendor_id',
        'item_name',
        'item_id',
        'quantity',
        'price',
        'total_amount',
        'po_created',
    ];

    public function admin_fas_basket_item(){
        return $this->belongsTo(AdminFasBasketItems::class,'id');
    }
}
