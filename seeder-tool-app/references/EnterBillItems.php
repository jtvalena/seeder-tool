<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnterBillItems extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'enterbill_items';
    protected $fillable = [
        'transaction_number',
        'enter_bill_ids',
        'transact_id',
        'transact_type_id',
        'total_amount',
        'balance',
        'paid_amount',
        'pending_amount',
        'amount_to_pay',
        'transact_item_id',
        'uom_id',
        'quantity',
        'item_price',
    ];

    public function prf_item(){
        return $this->belongsTo(PurchaseRequestItem::class, 'prf_item_id');
    }
    public function transactItem()
    {
        return $this->belongsTo(TravelAuthorizationFormItem::class, 'transact_item_id');
    }
}
