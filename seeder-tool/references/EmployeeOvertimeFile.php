<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\EmployeeOvertimeRecordItems;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class EmployeeOvertimeFile extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'employee_overtime_files';

    protected $fillable = [
        'id',
        'overtime_record_id',
        'ot_time_in',
        'file_path',
        'uploaded_by',
        'name'
    ];

    public function employee_overtime_record_items(){
        return $this->belong(EmployeeOvertimeRecordItems::class, 'id');
    }


}
