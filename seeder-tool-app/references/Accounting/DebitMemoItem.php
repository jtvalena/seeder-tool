<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Http\Models\Accounting\DebitMemo;

class DebitMemoItem extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $appends = [];

    protected $fillable = [
        'debit_memo_id',
        'item_id',
        'quantity',
        'unit_price',
        'total_amount',
        'remarks',
        'deleted_at',
    ];

    public function debit_memo()
    {
        return $this->belongsTo(DebitMemo::class);
    }
}
