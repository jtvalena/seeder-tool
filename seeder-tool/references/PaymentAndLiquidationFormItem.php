<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PaymentAndLiquidationFormItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_and_liquidation_form_id',
        'enter_bill_id',
        'reference_doc_number',
        'transact_id',
        'transact_type_id',
        'total_amount',
    ];

    public function PaymentAndLiquidationForm()
    {
        return $this->belongsTo(PaymentAndLiquidationForm::class, 'id');
    }
}
