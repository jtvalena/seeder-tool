<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerAdditionalItemDiscountCustomer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'scheme_id',
        'customer_id',
        'additional_discount_percent',
    ];

    protected $casts = [
        'additional_discount_percent' => 'decimal:4',
    ];

    public function scheme()
    {
        return $this->belongsTo(CustomerAdditionalItemDiscountScheme::class, 'scheme_id');
    }
}
