<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SalesIncentiveCriteria extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $table = 'sales_incentive_criteria';

    protected $fillable = [
        'name',
        'eligible_position_ids',
        'description',
        'base_amount',
        'base_target_source',
        'additional_amount_per_unit',
        'additional_amount_type',
        'additional_amount_multiplier',
        'additional_amount_unit_size',
        'additional_incentive_cap',
        'item_rule_enabled',
        'item_rule_filter',
        'item_rule_min_share_pct',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'eligible_position_ids' => 'array',
        'is_active' => 'boolean',
        'item_rule_enabled' => 'boolean',
        'base_amount' => 'decimal:2',
        'additional_amount_per_unit' => 'decimal:2',
        'additional_amount_multiplier' => 'decimal:2',
        'additional_amount_unit_size' => 'decimal:2',
        'additional_incentive_cap' => 'decimal:2',
        'item_rule_min_share_pct' => 'decimal:4',
    ];

    public function sales_incentives()
    {
        return $this->hasMany(SalesIncentive::class);
    }
}
