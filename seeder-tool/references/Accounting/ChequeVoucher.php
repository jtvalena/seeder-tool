<?php

namespace App\Models\Accounting;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ChequeVoucher extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes, Caching;

    protected $fillable = [
        'payment_voucher_id',
        'bank_id',
        'booklet_id',
        'vendor_id',
        'vendor_payment_item_id',
        'payee',
        'cheque_voucher_number',
        'cheque_number',
        'amount',
        'beginning_amount',
        'remarks',
        'printed',
        'pv_print',
        'cheque_date',
        'printed_by',
        'status'
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'cheque_voucher_selection',
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
