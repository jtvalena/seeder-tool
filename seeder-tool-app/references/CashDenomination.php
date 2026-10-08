<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\MorphMap;

class CashDenomination extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use MorphMap;
}
