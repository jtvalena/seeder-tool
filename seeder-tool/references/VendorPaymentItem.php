<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class VendorPaymentItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable, Caching;

    protected $fillable = [
        'vendor_id',
        'payee',
        'account_num',
        'terms',
        'payment_types_id',
        'active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'vendor_payment_items_selection',
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
