<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CreditBill extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'bank_card_id',
        'vendor_id',
        'company_id',
        'store_branches_id',
        'remarks',
        'billing_date',
        'due_date',
        'total_amount',
        'prepared_by',
        'approved_by',
        'status',
    ];
    public function credit_bill_items()
    {
        return $this->hasMany(CreditBillItem::class, 'credit_bill_id');
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $lastTransactionNumber = static::withTrashed()->max('transaction_number');
            $model->transaction_number = str_pad(
                $lastTransactionNumber ? 
                ++$lastTransactionNumber : 
                '00000001', 8, '0', STR_PAD_LEFT
            );
        });
    }
}
