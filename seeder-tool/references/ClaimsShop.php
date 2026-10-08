<?php

namespace App\Models;

use App\Models\MasterData\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ClaimsShop extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'claims_platform_id',
        'customer_id',
        'code',
        'name',
        'remarks',
        'active',
        'img',
    ];

    public function claims_platform()
    {
        return $this->belongsTo(ClaimsPlatform::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
