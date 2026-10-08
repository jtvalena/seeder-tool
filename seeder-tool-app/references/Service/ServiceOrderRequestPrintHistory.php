<?php

declare(strict_types=1);

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderRequestPrintHistory extends Model
{
    protected $table = 'service_order_request_print_histories';

    protected $fillable = [
        'sor_id',
        'printed_by',
        'print_type',
        'printed_at',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
    ];

    public function sor(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderRequest::class, 'sor_id');
    }
}
