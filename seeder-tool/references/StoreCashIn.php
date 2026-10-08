<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class StoreCashIn extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'company_id', 
        'store_branch_id', 
        'deposit_date', 
        'sales_date', 
        'remarks', 
        'status', 
        'total_amount', 
        'confirmed_by', 
        'confirmed_at', 
        'prepared_by',
        'cancelled_by',
        'cancelled_at',
        'sales_amount',
        'variance',
        'gross_sales',
        'twothreezeroseven',
        'asm_discount',
        'ingcoins',
        'tier_discount_ingcoins',
        'change_item_void',
    ];

    public function store_cash_in_details()
    {
        return $this->hasMany(StoreCashInDetail::class, 'store_cash_in_id');
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $lastTransactionNumber = static::withTrashed()->max('transaction_number');
            $nextNumber = 1;
            
            if ($lastTransactionNumber) {
                $nextNumber = (int)$lastTransactionNumber + 1;
            }
            
            $model->transaction_number = str_pad((string)$nextNumber, 6, '0', STR_PAD_LEFT);
        });
    }
}
