<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Currency extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'default',
        'active',
    ];

    public function details()
    {
        return $this->hasMany(CurrencyBank::class);
    }

    public function purchases()
    {
        return $this->hasMany(CurrencyPurchase::class);
    }

    public function exchange_rate_average()
    {
        return $this->hasMany(CurrencyExchangeRateAverage::class);
    }
}
