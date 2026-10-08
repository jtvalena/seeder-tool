<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\MasterData\Customer;
use App\Models\Sales\Promo;

class CustomerPromoItemRestriction extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'customer_id',
        'promo_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }
}
