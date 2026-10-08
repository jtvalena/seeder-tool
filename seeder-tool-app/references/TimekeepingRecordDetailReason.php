<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TimekeepingRecordDetailReason extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'timekeeping_record_detail_id',
        'reason',
        'user_id',
    ];

    public function timekeeping_record_detail(){
        return $this->belongsTo(TimekeepingRecordDetail::class, 'timekeeping_record_detail_id');
    }
}
