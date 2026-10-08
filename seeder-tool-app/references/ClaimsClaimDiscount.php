<?php

namespace App\Models;

use App\Models\Items\ItemCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class ClaimsClaimDiscount extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'claims_claim_id',
        'display_as',
        'item_category_ids',
        'base_discount',
        'subsidy_discount',
        'quantity',
        'selling_amount',
        'disc_price',
        'subsidy_amount',
        'diff_amount',
    ];

    protected $appends = [
        'item_categories',
    ];

    public function getItemCategoriesAttribute()
    {
        return ItemCategory::getQuery()->whereIn('id', json_decode($this->item_category_ids))->value(DB::raw('group_concat(name separator ", ")'));
    }
}
