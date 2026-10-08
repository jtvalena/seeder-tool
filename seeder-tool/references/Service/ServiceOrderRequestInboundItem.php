<?php

declare(strict_types=1);

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderRequestInboundItem extends Model
{
    protected $table = 'service_order_request_inbound_items';

    protected $fillable = [
        'sor_ib_id',
        'sor_id',
        'received',
        'received_by',
    ];

    protected $casts = [
        'received' => 'boolean',
    ];

    public function inbound(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderRequestInbound::class, 'sor_ib_id');
    }

    public function sor(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderRequest::class, 'sor_id');
    }
}
