<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimsClaimItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'claims_claim_id',
        'claims_template_item_id',
        'transaction_date',
        'transaction_number',
        'description',
        'item_id',
        'orig_item_id',
        'category_id',
        'quantity',
        'srp',
        'disc_price',
        'selling_amount',
        'base_amount',
        'subsidy_amount',
        'diff_amount',
        'disapprove_reason',
        'store_name',
    ];

    public function claims_claim()
    {
        return $this->belongsTo(ClaimsClaim::class);
    }

    public function claims_template_item()
    {
        return $this->belongsTo(ClaimsTemplateItem::class);
    }
}
