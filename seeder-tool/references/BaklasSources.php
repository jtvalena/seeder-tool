<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BaklasSources extends Model implements Auditable
{
    use \owenit\Auditing\Auditable;

    protected $fillable=[
        'source',
        'description',
    ];
}
