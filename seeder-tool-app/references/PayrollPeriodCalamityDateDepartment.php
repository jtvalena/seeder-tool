<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollPeriodCalamityDateDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'calamity_date_id',
        'context',
        'department_id',
    ];

    public function calamityDate()
    {
        return $this->belongsTo(PayrollPeriodCalamityDate::class, 'calamity_date_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
