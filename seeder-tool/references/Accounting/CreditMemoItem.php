<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Accounting\CreditMemo;
use App\Models\Items\Item;

class CreditMemoItem extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        'item_model',
        'item_name',
        'item_uom',
    ];

    protected $fillable = [
        'credit_memo_id',
        'item_id',
        'quantity_cm',
        'item_amount',
        'total_amount',
        'remarks',
        'item_cost',
        'total_cost'
    ];

    public function credit_memo()
    {
        return $this->belongsTo(CreditMemo::class, 'credit_memo_id')->withTrashed();
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id')->withTrashed();
    }

    public function getItemModelAttribute()
    {
        return $this->item->model ?? '';
    }

    public function getItemNameAttribute()
    {
        return $this->item->name ?? '';
    }

    public function getItemUomAttribute()
    {
        return $this->item->item_uom_text ?? '';
    }
}
