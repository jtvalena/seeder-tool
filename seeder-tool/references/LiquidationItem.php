<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class LiquidationItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'liquidation_id',
        'particulars',
        'date',
        'coa_id',
        'supplier_id',
        'amount',
        'from_supplier_list',
        'department_id',
        'job_id',
        'beginning_odometer',
        'ending_odometer',
    ];

    public function liquidation_item_files()
    {
        return $this->hasMany(LiquidationItemFile::class, 'liquidation_item_id');
    }
}
