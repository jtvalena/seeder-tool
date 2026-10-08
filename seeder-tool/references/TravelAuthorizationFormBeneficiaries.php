<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TravelAuthorizationFormBeneficiaries extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'taf_id',
        'employee_id',
        'given_name',
        'middle_name',
        'last_name',
        'department_id',
        'job_title',
    ];
}
