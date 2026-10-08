<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeDataFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable =[
        'name',
        'path',
        'employee_id',
        'uploaded_by',
    ];

}
