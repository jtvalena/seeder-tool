<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
class AdminItems extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'item_category',
        'item_name',
        'admin_classification_id',
        'admin_classification_sub_id',
        'admin_category_id',
        'sub_asset_num',
        'image_path',
        'remarks',
        'active',
        'model_number',
        'serial_number',
        'brand_id',
        'color_id',
        'item_specs',
        'consume_type',
        'created_by',
        'updated_by',
        'deleted_by',
        'service',
        'required_serial',
        'base_price',
        'is_packages',
        'department_id',
        'service_type',
        'for_vehicle',
        'from_system_id',
        'admin_item_size_id',
        'admin_model_id',
        'old_item_name',
        'duplicate_id',
    ];

    public function item_vendor_price_lists(){
        return $this->hasMany(ItemVendorPriceLists::class , 'item_id');
    }
    public function price_history(){
        return $this->hasMany(ItemVendorPriceLists::class , 'item_id');
    }
    public function item_components(){
        return $this->hasMany(AdminItemPackagesComponents::class , 'admin_item_id');
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'admin_puchase_order_items',
            'get_prf_and_fas_items_without_price'
        ];
        static::created((function ($model) use($cache_names) {
            $model->forgetByPattern('get_prf_admin_items:*');
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forgetByPattern('get_prf_admin_items:*');
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forgetByPattern('get_prf_admin_items:*');
            $model->forget($cache_names);
        }));
    }
}
