<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CurrencyExchangeRateAverage extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'doc_date',
        'currency_id_foreign',
        'currency_id_local',
        'exchange_rate',
    ];

    public function currency_foreign()
    {
        $this->belongsTo(Currency::class, 'currency_id_foreign');
    }

    public function currency_local()
    {
        $this->belongsTo(Currency::class, 'currency_id_local');
    }
}
