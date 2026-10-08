<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InternationalPaymentItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'international_payment_id',
        'container_id',
        'total_cost',
        'container_cost',
        'exchange_rate',
        'net_amount',
        'net_amount_php',
        'credit_memo_num',
        'sea_freight',
        'adjustment_amount',
        'spare_parts_cost',
        'beneficiary_bank_id',
        'source_bank_id',
        'container_type_id',
        'container_num',
        'credit_note_num',
        'broker_fee',
        'telex_number',
        'telex_date',
    ];
    
    public function international_payment_item_files()
    {
        return $this->hasMany(InternationalPaymentItemFile::class, 'international_payment_item_id');
    }
}
