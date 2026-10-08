<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ChequeBasket extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'remarks', 
        'deposit_date', 
        'deposit_bank_id', 
        'total_amount', 
        'status', 
        'prepared_by', 
        'last_updated_by', 
        'deleted_by', 
        'approved_by', 
        'approved_at',
    ];
    public function cheque_basket_items()
    {
        return $this->hasMany(ChequeBasketItem::class, 'cheque_basket_id');
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
