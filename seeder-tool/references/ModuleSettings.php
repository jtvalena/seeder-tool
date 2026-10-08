<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ModuleSettings extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'department_id',
        'parameter',
        'value',
        'display',
        'settings_type',
        'updated_by',
    ];
}
