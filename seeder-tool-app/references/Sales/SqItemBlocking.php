<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\Item;

class SqItemBlocking extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $table = 'sq_item_blockings';

    protected $fillable = [
        'item_id',
        'reason',
        'created_by',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
