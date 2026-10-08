<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class ScDisposalItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'item_id',
        'disposal_id',
        'quantity',
        'disposed_qty',
        'recovered_qty',
        'remaining_qty',
        'st_original_qty',
        'remarks',
        'correct_item_id',
    ];

    public function disposal() 
    {
        return $this->belongsTo(ScDisposal::class);
    }
}
