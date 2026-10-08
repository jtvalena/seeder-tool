<?php

declare(strict_types=1);

namespace App\Models\Service;

use App\Models\Service\ServiceOrderRequest;
use App\Models\Service\ServiceOrderRequestOutbound;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderRequestOutboundItem extends Model
{
    protected $table = 'service_order_request_outbound_items';

    protected $fillable = [
        'sor_ob_id',
        'sor_id',
        'confirmed',
        'confirmed_by',
    ];

    protected $casts = [
        'confirmed' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function outbound(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderRequestOutbound::class, 'sor_ob_id');
    }

    public function sor(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderRequest::class, 'sor_id');
    }
}
