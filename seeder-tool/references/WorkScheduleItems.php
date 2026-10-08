<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WorkScheduleItems extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'work_schedule_template_id',
        'department_id',
        'job_title_id',
        'by_position',
    ];

    public function work_schedule_template(){
        return $this->belongsTo(WorkScheduleTemplate::class, 'work_schedule_template_id');
    }
}
