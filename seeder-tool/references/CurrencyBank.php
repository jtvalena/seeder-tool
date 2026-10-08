<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CurrencyBank extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'currency_id',
        'source_bank_id_local',
        'destination_bank_id_local',
        'bank_id_foreign',
        'default',
    ];
}
