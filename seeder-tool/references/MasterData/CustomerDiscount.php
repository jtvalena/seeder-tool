<?php

declare(strict_types=1);

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class CustomerDiscount extends Model
{
    protected $table = 'customer_discounts';

    protected $fillable = [
        'customer_id',
        'discount_percentage',
        'eligible_item_types',
    ];

    protected $casts = [
        'discount_percentage' => 'float',
    ];

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Parse the comma-separated eligible_item_types string into an array of integer IDs.
     * Returns an empty array when no restriction is configured (all item types are eligible).
     *
     * Example stored value: "1,2" or "1, 3" → [1, 2] / [1, 3]
     */
    public function eligibleItemTypeIds(): array
    {
        if (empty($this->eligible_item_types)) {
            return [];
        }

        return array_values(array_filter(
            array_map('intval', explode(',', $this->eligible_item_types)),
            fn (int $id): bool => $id > 0
        ));
    }
}
