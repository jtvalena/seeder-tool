<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AdminWarehouseStocks extends Model
{
    use HasFactory, SoftDeletes, Caching;
    protected $guarded = ['admin_warehouse_stocks'];

    public static function boot()
    {
        parent::boot();

        $cache_names = [
            'admin_stock_from_po',
            'selected_admin_stock_from_po',
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
