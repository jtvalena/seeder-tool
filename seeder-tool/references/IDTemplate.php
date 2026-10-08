<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class IDTemplate extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'id_templates';
}
