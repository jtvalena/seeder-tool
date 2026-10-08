<?php

namespace App\Models;

use App\Traits\TimekeepingTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeOBRecordItems extends Model implements Auditable
{
    use HasFactory, TimekeepingTrait;
    use \OwenIt\Auditing\Auditable;


    protected $table = 'employee_ob_record_items';

    protected $fillable = [
        'ob_record_id',
        'approved_by',
        'approved_date',
        'rejected_by',
        'rejected_date',
        'reject_reason',
        'ob_date',
        'time_in',
        'time_out',
        'destination',
        'purpose',
        'status',
        'diff_location',
        'store_branch_id',
    ];

    protected $appends = [
        'editActive',
        'for_emergency'
    ];

    public function employee_ob_record(){
        return $this->belongsTo(EmployeeOBRecord::class, 'ob_record_id');
    }

    public function employee_ob_record_item_files(){
        return $this->hasMany(EmployeeOBRecordItemFiles::class);
    }

    public function getEditActiveAttribute(){
        return false;
    }

    public function getForEmergencyAttribute(){
        return $this->checkIfDateIsNotWithinCurrentPayroll($this->ob_date);
    }

    // Status
    // 1 - pending
    // 2 - rejected
    // 3 - approved
    // 4 - confirmed
}
