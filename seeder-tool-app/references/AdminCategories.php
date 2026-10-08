<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class AdminCategories extends Model implements Auditable
{
    use HasFactory, Caching;
    use \OwenIt\Auditing\Auditable;

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
