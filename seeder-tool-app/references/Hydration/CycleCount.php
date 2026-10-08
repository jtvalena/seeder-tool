<?php

namespace App\Models\Hydration;

use App\Enums\ScheduleInterval;
use Illuminate\Database\Eloquent\Model;

class CycleCount extends Model
{
    protected $casts = [
        'warehouse_id' => 'int',
        'user_id' => 'int',
        'type' => 'int',
        'date' => 'string',
        'status' => 'int',
        'remarks' => 'string',
        'auto_approve' =>'boolean'
    ];

    protected $appends = [
        'status',
        'schedule_interval',
    ];

    /**
     * Transform Auto Approve into Status
     * 0 : For Approval
     * 1 : Approved
     *
     * @return int
     */
    public function getStatusAttribute()
    {
        return $this->auto_approve ? 1 : 0;
    }

    public function getScheduleIntervalAttribute()
    {
        return (isset($this->interval_type) && !is_null($this->interval_type))
            ? ScheduleInterval::fromValue($this->interval_type)->value
            : null;
    }
}
