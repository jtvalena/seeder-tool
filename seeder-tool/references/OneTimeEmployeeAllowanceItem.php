<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneTimeEmployeeAllowanceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'one_time_employee_allowance_id',
        'allowance_type_id',
        'description',
        'amount',
        'effectivity_date',
    ];

    public function oneTimeAllowance()
    {
        return $this->belongsTo(OneTimeEmployeeAllowance::class, 'one_time_employee_allowance_id', 'id');
    }
}
