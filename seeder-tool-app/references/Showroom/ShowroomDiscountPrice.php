<?php

declare(strict_types=1);

namespace App\Models\Showroom;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShowroomDiscountPrice extends Model
{
    protected $fillable = [
        'item_id',
        'discounted_price',
    ];

    protected $casts = [
        'item_id' => 'integer',
        'discounted_price' => 'decimal:2',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
