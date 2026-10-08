<?php

namespace App\Models\Operations;

use App\Enums\CycleCountType;
use App\Enums\ScheduleInterval;
use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class CycleCount extends Model implements Auditable
{
    use HasFactory, AuditableTrait;
    use MorphMap,HasAttributeChangesToBroadcast;

    protected $fillable = [
        'warehouse_id',
        'user_id',
        'type',
        'schedule_interval',
        'date',
        'status',
        'remarks',
        'location'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'cycle_count_form_number',
        'form_number',
        'type_description',
        'morphMapKey',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(CycleCountDetails::class, 'cycle_count_id');
    }

    public function getTypeDescriptionAttribute()
    {
        $description = '';

        switch ($this->type){
            case CycleCountType::NONE_SCHEDULED:
                $description = CycleCountType::NONE_SCHEDULED()->description;
                break;
            case CycleCountType::CUSTOM_SCHEDULED:
                $description = CycleCountType::CUSTOM_SCHEDULED()->description;
                break;
            case CycleCountType::SCHEDULED_BY_INTERVAL:
                $description = ScheduleInterval::fromValue($this->schedule_interval)->description;
        }

        return strtoupper($description);
    }

    public function getCycleCountFormNumberAttribute()
    {
        $warehouseCode = strtoupper($this->warehouse->code);
        $number = str_pad($this->id,10, '0',STR_PAD_LEFT);

        return "CCF# {$warehouseCode}{$this->id}";
    }

    public function getFormNumberAttribute()
    {
        $warehouseCode = strtoupper($this->warehouse->code);
        $number = str_pad($this->id,10, '0',STR_PAD_LEFT);

        return "{$warehouseCode}{$this->id}";
    }
}
