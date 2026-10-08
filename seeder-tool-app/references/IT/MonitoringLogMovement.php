<?php

declare(strict_types=1);

namespace App\Models\IT;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringLogMovement extends Model
{
    protected $fillable = [
        'monitoring_log_id',
        'status_id',
        'assigned_it_id',
        'action',
        'remarks',
        'updated_by_id',
    ];

    public function monitoringLog(): BelongsTo
    {
        return $this->belongsTo(MonitoringLog::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(MonitoringLogStatus::class, 'status_id');
    }

    public function assignedIt(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_it_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
