<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\MorphMap;
use App\Models\Accounting\CustomerPaymentDetail;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Caching;

class CustomerPaymentAllocation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;
    use SoftDeletes;
    use MorphMap;

    protected $fillable = [
        'soa_id',
        'customer_payment_detail_id',
        'allocated_wht',
        'allocated_amount',
        'allocated_by',
        'allocate_limit',
        'cancel_reason',
    ];

    public function customer_payment_detail() {
        return $this->belongsTo(CustomerPaymentDetail::class);
    }

    public function customer_soa_payment()
    {
        return $this->hasOne(\App\Models\CustomerSoaPayment::class, 'customer_payment_allocation_id');
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'statement_of_account_selection',
        ];
        static::creating(function ($model) use($cache_names) {
            $model->forget($cache_names);
        });

        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));

        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }
}
