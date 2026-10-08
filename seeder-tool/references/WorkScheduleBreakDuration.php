<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WorkScheduleBreakDuration extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'work_schedule_template_id',
        'type',
        'time_in',
        'time_out',
        'duration',
    ];

    public function work_schedule_template(){
        return $this->belongsTo(WorkScheduleTemplate::class);
    }

    protected $casts = [
        'time_in' => 'date:H:i',
        'time_out' => 'date:H:i',
    ];

    // Types
    // 1 - AM Break
    // 2 - PM Break
    // 3 - Lunch Break
}
