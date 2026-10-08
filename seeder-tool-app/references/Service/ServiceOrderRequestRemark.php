<?php

declare(strict_types=1);

namespace App\Models\Service;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderRequestRemark extends Model
{
    protected $table = 'service_order_request_remarks';

    protected $fillable = [
        'sor_id',
        'remark',
        'user_id',
        'user_name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sor(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderRequest::class, 'sor_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
