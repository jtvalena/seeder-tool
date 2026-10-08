<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\Caching;

class CustomerSoaPayment extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable, Caching;

    protected  $table = 'customer_soa_payments';

    protected $fillable = [
        'payment_type_id',
        'soa_id',
        'customer_payment_detail_id',
        'customer_payment_allocation_id',
        'control_number',
        'amount',
        'confirmed',
    ];

    public function customer_payment_allocation()
    {
        return $this->belongsTo(\App\Models\Accounting\CustomerPaymentAllocation::class, 'customer_payment_allocation_id');
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
