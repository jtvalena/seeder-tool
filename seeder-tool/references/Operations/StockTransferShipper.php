<?php

namespace App\Models\Operations;

use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\Operations\StockTransfer;
use App\Models\Vehicle;

class StockTransferShipper extends Model implements Auditable
{
    use HasFactory, AuditableTrait, MorphMap, HasAttributeChangesToBroadcast;

    protected $fillable = [
        'stock_transfer_id',
        'vehicle_id',
        'driver',
        'helpers'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'helpers' => 'array'
    ];

    protected $appends = [
        'morphMapKey'
    ];

    public function stockTransfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
