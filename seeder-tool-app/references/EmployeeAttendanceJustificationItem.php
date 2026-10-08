<?php

namespace App\Models;

use App\Traits\TimekeepingTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeAttendanceJustificationItem extends Model implements Auditable
{
    use HasFactory;
    use TimekeepingTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_attendance_justification_id',
        'approved_by',
        'approved_date',
        'rejected_by',
        'rejected_date',
        'reject_reason',
        'eaj_date',
        'time_in',
        'time_out',
        'break_out',
        'break_in',
        'destination',
        'purpose',
        'status',
        'diff_location',
        'store_branch_id',
    ];

    protected $appends = [
        'editActive',
        'for_emergency',
    ];

    public function employee_attendance_justification(){
        return $this->belongsTo(EmployeeAttendanceJustification::class, 'employee_attendance_justification_id');
    }

    public function employee_attendance_justification_item_files(){
        return $this->hasMany(EmployeeAttendanceJustificationFile::class);
    }

    public function getEditActiveAttribute(){
        return false;
    }

    public function getForEmergencyAttribute(){
        return $this->checkIfDateIsNotWithinCurrentPayroll($this->eaj_date);
    }

    // Status
    // 1 - pending
    // 2 - rejected
    // 3 - approved
    // 4 - confirmed
}
