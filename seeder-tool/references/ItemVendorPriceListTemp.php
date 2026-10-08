<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemVendorPriceListTemp extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "item_vendor_price_list_temps";
    protected $fillable = [
        'item_vendor_price_list_id',
        'vendor_id',
        'item_id',
        'price',
        'uom_id', 
        'created_by', 
        'updated_by', 
        'moq', 
        'discard_at',
        'admin_po_num',
        'date_updated',
        'approved'
    ];
}
