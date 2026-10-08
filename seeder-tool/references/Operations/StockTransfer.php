<?php

namespace App\Models\Operations;

use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\User;
use App\Models\Operations\Warehouse;
use App\Models\Operations\StockRequest;
use App\Models\Operations\StockTransferShipper;
use App\Models\Operations\StockTransferStatus;

class StockTransfer extends Model implements Auditable
{
    use HasFactory, AuditableTrait, MorphMap, HasAttributeChangesToBroadcast;

    protected $fillable = [
        'stock_request_id',
        'user_id',
        'warehouse_id',
        'remarks',
        'approved',
        'confirm_received_on_device',
        'service'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'morphMapKey',
        'latest_status',
        'latest_status_detail',
        'stock_transfer_form_number'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function claimant(): BelongsTo
    {
        return $this->belongsTo(StockRequest::class, 'stock_request_id');
    }

    public function shipper(): HasOne
    {
        return $this->hasOne(StockTransferShipper::class, 'stock_transfer_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(StockTransferDetails::class, 'stock_transfer_id');
    }

    public function status(): HasMany
    {

        return $this->hasMany(StockTransferStatus::class, 'stock_transfer_id');
    }

    public function getLatestStatusAttribute(): int
    {
        return $this->status()->latest()->first()->status;
    }

    public function getLatestStatusDetailAttribute(): array
    {
        $status = $this->status()->latest()->first();

        return [
            'status' => $status->status,
            'date' => Carbon::parse($status->date)->toDateTimeString()
        ];
    }

    public function getStockTransferFormNumberAttribute()
    {
        $warehouseCode = strtoupper($this->warehouse->code);
        $number = str_pad($this->id,10, '0',STR_PAD_LEFT);

        return "STF# {$warehouseCode}-{$this->id}";
    }

    public function getFormNumberAttribute(){
        $warehouseCode = strtoupper($this->warehouse->code);
        $number = str_pad($this->id, 10, '0', STR_PAD_LEFT);

        return "{$warehouseCode}-{$this->id}";
    }
}
