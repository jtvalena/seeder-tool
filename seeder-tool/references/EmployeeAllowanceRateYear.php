<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeAllowanceRateYear extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_allowance_rate_id',
        'year_rate',
        'year',
    ];

    protected $casts = [
        'year' => 'int',
    ];
}
