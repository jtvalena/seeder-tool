<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\EmployeeAllowanceRateYear;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeAllowanceRate extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'allowance_type_id',
        'allowance_frequency_type_id',
        'frequency_parameter',
        'rate',
    ];

    protected $appends = [
        'allowance_name'
    ];

    public function employee_allowance_rate_years(){
        return $this->hasMany(EmployeeAllowanceRateYear::class, 'employee_allowance_rate_id', 'id');
    }

    public function getAllowanceNameAttribute(){
        $allowance = AllowanceType::getQuery()->find($this->allowance_type_id);
        if($allowance){
            return $allowance->name;
        }
        return '';
    }
}
