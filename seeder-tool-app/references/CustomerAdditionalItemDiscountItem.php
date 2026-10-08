<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerAdditionalItemDiscountItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'scheme_id',
        'item_id',
    ];

    public function scheme()
    {
        return $this->belongsTo(CustomerAdditionalItemDiscountScheme::class, 'scheme_id');
    }

    public function item()
    {
        return $this->belongsTo(AdminItems::class, 'item_id');
    }
}
