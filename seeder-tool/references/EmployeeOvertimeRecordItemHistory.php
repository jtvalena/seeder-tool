<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeOvertimeRecordItemHistory extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_overtime_record_item_id',
        'updated_by',
        'ot_time_in',
        'ot_time_out',
        'ot_minutes',
        'regular_rate_minutes',
        'ot_rate_minutes',
        'reason',
        'status',
    ];

    protected $appends = [
        'updated_by_name',
        'status_text',
    ];

    public function getUpdatedByNameAttribute(){
        $name = User::where('id', $this->updated_by)->value('name');
        return $name ?? '';
    }

    public function getStatusTextAttribute(){
        $status = $this->status;
        switch($status){
            case 1:
                return 'pending';
            case 3:
                return 'approved';
            case 5:
                return 'modified';
            default:
                return '';
        }
    }
}
