<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreHrAccess extends Model
{
    use HasFactory;

    protected $table = 'hr_user_store_accesses';

    protected $fillable = [
        'hr_user_id',
        'employee_id',
        'full_name',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }
}
