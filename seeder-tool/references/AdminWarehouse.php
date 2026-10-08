<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AdminWarehouse extends Model
{
    use HasFactory, SoftDeletes, Caching;

    protected $table = 'admin_warehouses';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'admin_warehouse_code_dropdown',
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
