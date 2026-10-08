<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeFamilyMembersTemp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_data_temp_id',
        'name',
        'address',
        'relationship',
        'occupation',
    ];
}
