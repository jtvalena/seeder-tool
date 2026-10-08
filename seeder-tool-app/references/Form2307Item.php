<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Form2307Item extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'form_2307_items';

    protected $fillable = [
        'form_2307_id',
        'payment_voucher_id',
        'payment_voucher_detail_id',
        'amount'
    ];
}

