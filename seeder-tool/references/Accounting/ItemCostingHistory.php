<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class ItemCostingHistory extends Model
{
    protected $fillable = [
        'item_id',
        'old_cost',
        'new_cost',
        'user_id',
    ];
}
