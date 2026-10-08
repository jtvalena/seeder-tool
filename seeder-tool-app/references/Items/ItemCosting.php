<?php

namespace App\Models\Items;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
// use Mavinoo\Batch\Traits\HasBatch;

class ItemCosting extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // use HasBatch;

    protected $table = 'item_costing';

    protected $fillable = [
        'item_id',
        'cost_in_foreign',
        'cost_updated_at',
        'cost_updated_by',
        'currency_id',
        'foc',
    ];

}
