<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TechnicalReport extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
