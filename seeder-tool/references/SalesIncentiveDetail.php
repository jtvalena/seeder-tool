<?php

namespace App\Models;

use App\Models\MasterData\Salesman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SalesIncentiveDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'sales_incentive_id',
        'salesman_id',
        'sales_target',
        'actual_sales',
        'excess_amount',
        'base_incentive',
        'additional_incentive',
        'total_incentive',
        'item_rule_filtered_amount',
        'item_rule_actual_pct',
        'item_rule_deduction',
        'item_rule_applied',
        'remarks',
    ];

    protected $casts = [
        'sales_target' => 'decimal:2',
        'actual_sales' => 'decimal:2',
        'excess_amount' => 'decimal:2',
        'base_incentive' => 'decimal:2',
        'additional_incentive' => 'decimal:2',
        'total_incentive' => 'decimal:2',
        'item_rule_filtered_amount' => 'decimal:2',
        'item_rule_actual_pct' => 'decimal:4',
        'item_rule_deduction' => 'decimal:2',
        'item_rule_applied' => 'boolean',
    ];

    public function sales_incentive()
    {
        return $this->belongsTo(SalesIncentive::class);
    }

    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }
}
