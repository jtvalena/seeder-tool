<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
class ItemVendorPriceLists extends Model implements Auditable
{
    use HasFactory,SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'vendor_id',
        'item_id',
        'brand',
        'price',
        'particulars',
        'quantity',
        'uom_id', 
        'created_by', 
        'updated_by', 
        'moq', 
        'discard_at',
        'admin_po_num',
        'temp'
    ];


    public static function boot()
    {
        parent::boot();
        static::created((function ($model) {
            $model->forgetByPattern('get_prf_admin_items:*');
        }));
        static::updated((function ($model) {
            $model->forgetByPattern('get_prf_admin_items:*');
        }));
        static::deleted((function ($model) {
            $model->forgetByPattern('get_prf_admin_items:*');
        }));
    }
}
