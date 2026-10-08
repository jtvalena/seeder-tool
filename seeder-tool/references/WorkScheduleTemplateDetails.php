<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkScheduleTemplateDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_schedule_template_id',
        'time_in',
        'time_out',
        'day',
        'hours_to_work_day',
        'isRestDay',
        'date',
    ];

    // protected $casts = [
    //     'time_in' => 'date:H:i',
    //     'time_out' => 'date:H:i',
    // ];
}
