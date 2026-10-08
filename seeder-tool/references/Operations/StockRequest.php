<?php

namespace App\Models\Operations;

use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\User;
use App\Models\Operations\Warehouse;
use App\Models\Operations\StockRequestDetails;
use App\Models\Operations\StockTransfer;

class StockRequest extends Model implements Auditable
{
    use HasFactory, AuditableTrait;
    use MorphMap,HasAttributeChangesToBroadcast;

    protected $fillable = [
        'user_id',
        'warehouse_id',
        'initial_serving_warehouse_id',
        'date',
        'remarks',
        'status',
        'service',
        'expected_date',
        'allow_butal',
        'baklas',
        'hakot_type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',

    ];

    protected $appends = [
        'stock_request_form_number',
        'form_number',
        'morphMapKey',
        'hakot_type_text',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function serving_warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'initial_serving_warehouse_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(StockRequestDetails::class, 'stock_request_id');
    }

    public function receivables(): HasMany
    {
        return $this->hasMany(StockTransfer::class, 'stock_request_id');
    }

    public function isEitherApprovedOrInProgress(): bool
    {
        return $this->status == 1 || $this->status == 2 || $this->staus == 3;
    }

    public function getStockRequestFormNumberAttribute()
    {
        $warehouseCode = strtoupper($this->warehouse->code);
        $number = str_pad($this->id, 10, '0', STR_PAD_LEFT);

        return "SRF# {$warehouseCode}-{$this->id}";
    }

    public function getFormNumberAttribute(){
        $warehouseCode = strtoupper($this->warehouse->code);
        $number = str_pad($this->id, 10, '0', STR_PAD_LEFT);

        return "{$warehouseCode}-{$this->id}";
    }

    public function getHakotTypeTextAttribute() {
        if($this->hakot_type == 0){
            return 'None';
        }elseif($this->hakot_type == 1){
            return 'Hakot (Replenishment)';
        }elseif($this->hakot_type == 2){
            return 'Hakot (Rush)';
        }
    }
}
