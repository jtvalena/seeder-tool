<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class AdminAssetsList extends Model implements Auditable
{
    use HasFactory, Caching;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'asset_num',
        'asset_code',
        'emp_id',
        'name',
        // 'tag_print_base69',
        // 'qr_code_base69',
        'supplier_id',
        'item_id',
        'admin_purchase_order_item_id',
        'admin_fas_purchase_requisition_item_id',
        'category_id',
        'status',
        'active',
        // 'brand_id',
        'department_id',
        'location_id',
        'serial',
        // 'classification_id',
        // 'sub_classification_id',
        'warranty',
        // 'cost',
        'purchase_date',
        'description',
        'company_id',
        'store_branch_id',
        'created_at',
        'updated_at',
        'accounted',
        'warehouse_id',
        'admin_warehouse_item_stocks_id',
        'brand',
        'item_serial_number',
        'no_serial',
        'basket_item_id',
        'manual_add',
        'printed',
        'classification_id',
        'sub_classification_id',
        'purchase_price',
        'purchase_order_reference',
        'from_import',
        'placed',
        'add_to_stock',
        'parent_asset_id',
        'from_sysmtem_id',
        'transfered_by',
        'from_asset_id',
        'other_system_po_num',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'po_number_asset_selection',
            'employee_data_dropdown_with_asset',
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }
}
