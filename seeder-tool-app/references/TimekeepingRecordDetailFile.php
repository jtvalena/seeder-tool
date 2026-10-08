<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimekeepingRecordDetailFile extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'timekeeping_record_detail_id',
        'timekeeping_file_type_id',
        'uploaded_by',
        'name',
        'path',
    ];

    public function timekeeping_record_detail(){
        return $this->belongsTo(TimekeepingRecordDetail::class, 'timekeeping_record_detail_id');
    }
}
