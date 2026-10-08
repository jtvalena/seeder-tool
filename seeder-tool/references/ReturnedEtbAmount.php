<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ReturnedEtbAmount extends Model implements  Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'etb_id',
        'total_amount',
        'reported_amount',
        'returned_amount',
        'difference_amount',
        'remarks'
    ];
    public function returned_amounts()
    {
        return $this->belongsTo(EmployeeTravelBudget::class, 'etb_id', 'id');
    }
}
