<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\Operations\StockRequest;
use App\Models\Items\Item;
class StockRequestDetails extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'stock_request_id',
        'item_id',
        'request_type',
        'request_type_quantity',
        'requested_uom_quantity',
        'remaining_uom_quantity',
        'receive_uom_quantity',
        'cancelled_quantity'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'requested_formatted',
        'remaining_formatted'
    ];

    public function stockRequest(): BelongsTo
    {
        return $this->belongsTo(StockRequest::class, 'stock_request_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function getRequestedFormattedAttribute()
    {
        return number_format($this->requested_uom_quantity, 0);
    }

    public function getRemainingFormattedAttribute()
    {
        return number_format($this->remaining_uom_quantity, 0);
    }
}
