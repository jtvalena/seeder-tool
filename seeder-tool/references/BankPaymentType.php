<?php

namespace App\Models;

use App\Models\Accounting\Bank;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BankPaymentType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function banks()
    {
        return $this->belongsTo(Bank::class);
    }
}


