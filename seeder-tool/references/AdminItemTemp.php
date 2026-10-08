<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminItemTemp extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "admin_item_temps";
    protected $fillable = [
        'admin_item_id',
        'item_category',
        'item_name',
        'admin_classification_id',
        'admin_classification_sub_id',
        'admin_category_id',
        'image_path',
        'remarks',
        'active',
        'model_number',
        'serial_number',
        'brand_id',
        'consume_type',
        'created_by',
        'updated_by',
        'deleted_by',
        'service',
        'required_serial',
        'date_updated',
        'approved',
        'base_price',
        'is_packages',
        'color_id',
        'item_specs',
    ];

    public function item_vendor_price_lists(){
        return $this->hasMany(ItemVendorPriceListTemp::class , 'item_id');
    }
    public function price_history(){
        return $this->hasMany(ItemVendorPriceListTemp::class , 'item_id');
    }
}
