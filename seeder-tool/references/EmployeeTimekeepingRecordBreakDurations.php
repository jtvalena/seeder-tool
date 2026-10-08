<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeTimekeepingRecordBreakDurations extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;


    protected $fillable = [
        'timekeeping_record_detail_id',
        'punch_time',
        'punch_state',
        'location',
    ];

    protected $appends = [
        'editActive'
    ];

    public function getEditActiveAttribute(){
        return false;
    }
}
