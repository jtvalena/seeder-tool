<?php

namespace App\Models\Operations;
use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CycleCountDetails extends Model
{
    protected $fillable = [
        'cycle_count_id',
        'item_id',
        'system_inventory',
        'actual_inventory',
        'variance',
        'status',
        'remarks',
        'previous_actual_inventory',
        'location'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'system_inventory_formatted',
        'actual_inventory_formatted',
        'previous_actual_inventory_formatted'
    ];

    public function cycleCount(): BelongsTo
    {
        return $this->belongsTo(CycleCount::class, 'cycle_count_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function getSystemInventoryFormattedAttribute()
    {
        return number_format($this->system_inventory, 0);
    }

    public function getActualInventoryFormattedAttribute()
    {
        return number_format($this->actual_inventory, 0);
    }

    public function getPreviousActualInventoryFormattedAttribute()
    {
        return number_format($this->previous_actual_inventory, 0);
    }
}
