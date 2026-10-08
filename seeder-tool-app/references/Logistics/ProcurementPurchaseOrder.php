<?php

namespace App\Models\Logistics;

use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\User;
use App\Models\Logistics\ProcurementPurchaseOrderDetails;

class ProcurementPurchaseOrder extends Model implements Auditable
{
    use HasFactory, AuditableTrait;
    use MorphMap,HasAttributeChangesToBroadcast;

    protected $fillable = [
        'date',
        'series',
        'po_number',
        'remarks',
        'supplier_id',
        'user_id',
        'status',
        'estemated_time_departure',
        'fulfillment_Date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'number_attribute',
        'number_prefix',
        'supplier',
        'supplier_address',
        'morphMapKey'
    ];

    public function getNumberPrefixAttribute()
    {
        return 'INGPO';
    }

    public function getNumberAttributeAttribute()
    {
        return 'po_number';
    }

    /**
     * TODO
     * Relate Supplier Model
     */
    public function getSupplierAttribute()
    {
        return 'INGCO China';
    }

    public function getSupplierAddressAttribute()
    {
        return 'Mainland China';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ProcurementPurchaseOrderDetails::class, 'procurement_purchase_order_id');
    }
}
