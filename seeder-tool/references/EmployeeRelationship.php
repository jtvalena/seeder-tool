<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeRelationship extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    protected $fillable = [
        'code',
        'name',
        'active',
    ];
}
