<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeStoreBranch extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
