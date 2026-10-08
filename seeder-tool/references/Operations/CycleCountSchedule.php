<?php

namespace App\Models\Operations;

use App\Models\Items\ItemTemplate;
use App\Models\Service\ScheduleModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class CycleCountSchedule extends ScheduleModel implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'warehouse_id',
        'user_id',
        'auto_approve',
        'type',
        'interval_type',
        'interval_day',
        'custom_done',
        'date',
        'time',
        'schedule_description',
        'item_filter_description',
        'template_id',
        'item_filter'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ItemTemplate::class, 'template_id');
    }
}
