<?php

declare(strict_types=1);

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderRequestPart extends Model
{
    use HasFactory;

    protected $table = 'service_order_request_parts';

    protected $fillable = [
        'sor_id',
        'model',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price'    => 'decimal:2',
    ];

    public function sor(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderRequest::class, 'sor_id');
    }
}
