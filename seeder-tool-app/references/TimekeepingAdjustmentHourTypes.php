<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimekeepingAdjustmentHourTypes extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'timekeeping_record_detail_id',
        'old_values',
        'new_values',
        'hour_types',
        'updated_by',
    ];

    public function timekeeping_record_detail()
    {
        return $this->belongsTo(TimekeepingRecordDetail::class, 'timekeeping_record_detail_id');
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
