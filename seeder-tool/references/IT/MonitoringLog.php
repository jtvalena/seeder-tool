<?php

declare(strict_types=1);

namespace App\Models\IT;

use App\Models\Department;
use App\Models\EmployeeData;
use App\Models\MonitoringLogFile;
use App\Models\StoreBranch;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonitoringLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_no',
        'log_date',
        'location_type',
        'department_id',
        'store_branch_id',
        'store_branch_connection',
        'store_branch_name',
        'area_id',
        'reported_by_id',
        'reported_by_name',
        'concern_type_id',
        'assigned_it_id',
        'status_id',
        'concern_details',
        'resolution_remarks',
        'created_by_id',
        'updated_by_id',
        'confirmed_by_id',
        'confirmed_at',
    ];

    protected $casts = [
        'log_date' => 'date',
        'confirmed_at' => 'datetime',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(MonitoringLogArea::class, 'area_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function storeBranch(): BelongsTo
    {
        return $this->belongsTo(StoreBranch::class, 'store_branch_id');
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(EmployeeData::class, 'reported_by_id');
    }

    public function assignedIt(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_it_id');
    }

    public function concernType(): BelongsTo
    {
        return $this->belongsTo(MonitoringLogConcernType::class, 'concern_type_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(MonitoringLogStatus::class, 'status_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(MonitoringLogMovement::class)->orderByDesc('created_at');
    }

    public function files(): HasMany
    {
        return $this->hasMany(MonitoringLogFile::class);
    }
}
