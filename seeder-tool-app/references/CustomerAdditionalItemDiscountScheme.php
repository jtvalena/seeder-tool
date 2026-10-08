<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerAdditionalItemDiscountScheme extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function customers()
    {
        return $this->hasMany(CustomerAdditionalItemDiscountCustomer::class, 'scheme_id');
    }

    public function items()
    {
        return $this->hasMany(CustomerAdditionalItemDiscountItem::class, 'scheme_id');
    }
}
