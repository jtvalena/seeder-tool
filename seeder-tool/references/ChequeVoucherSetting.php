<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ChequeVoucherSetting extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['days','color_code','active'];
}
