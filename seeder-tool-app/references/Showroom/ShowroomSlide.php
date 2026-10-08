<?php

declare(strict_types=1);

namespace App\Models\Showroom;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShowroomSlide extends Model
{
    protected $fillable = [
        'item_id',
        'specs_override',
        'discount_label',
        'final_price',
        'image_path',
        'headline_1',
        'headline_2',
        'display_duration_seconds',
        'sort_order',
        'is_enabled',
    ];

    protected $casts = [
        'item_id' => 'integer',
        'final_price' => 'decimal:2',
        'display_duration_seconds' => 'integer',
        'sort_order' => 'integer',
        'is_enabled' => 'boolean',
        'headline_1' => 'array',
        'headline_2' => 'array',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
