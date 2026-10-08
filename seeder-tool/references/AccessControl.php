<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AccessControl extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = "accesscontrols";

    protected $guarded = [];
}
