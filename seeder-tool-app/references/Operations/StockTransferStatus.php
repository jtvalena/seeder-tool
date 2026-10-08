<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\Operations\StockTransfer;
class StockTransferStatus extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'stock_transfer_id',
        'status',
        'date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function stockTransfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }
}
