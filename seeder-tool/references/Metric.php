<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Metric extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
