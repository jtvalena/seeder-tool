<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LeaveRequestChanges extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'leave_request_id',
        'old_leave_type_id',
        'new_leave_type_id',
        'changed_by',
        'change_reason',
    ];

    protected $appends = [
        'old_leave_type_name',
        'new_leave_type_name',
    ];

    public function leave_request(){
        return $this->belongsTo(LeaveRequest::class);
    }

    public function getOldLeaveTypeNameAttribute(){
        return LeaveType::where('id', $this->old_leave_type_id)->value('name');
    }

    public function getNewLeaveTypeNameAttribute(){
        return LeaveType::where('id', $this->new_leave_type_id)->value('name');
    }
}
