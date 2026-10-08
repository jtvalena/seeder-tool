<?php

namespace App\Models;

use App\Traits\Caching;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminJobOrderItems extends Model implements Auditable
{
    use HasFactory, Caching;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'admin_job_order_items';
    protected $fillable = [
        'admin_item_id',
        'item_vendor_price_list_id',
        'job_order_ids',
        'job_order_number',
        'quantity',
        'total_amount',
        'has_dimension',
        'item_overall_amount',
        'item_paid_amount',
        'balance_amount',
        'billed_amount',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'admin_job_order_items_selection',
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
