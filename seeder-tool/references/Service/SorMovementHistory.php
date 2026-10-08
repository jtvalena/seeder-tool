<?php

declare(strict_types=1);

namespace App\Models\Service;

use App\Models\Service\ScLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SorMovementHistory extends Model
{
    protected $table = 'sor_movement_histories';

    protected $fillable = [
        'sor_id',
        'type',
        'ref_num',
        'sc_location_receiving_id',
        'sc_location_repairing_id',
        'status',
        'processed_by',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function sor(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderRequest::class, 'sor_id');
    }

    public function fromBranch(): BelongsTo
    {
        return $this->belongsTo(ScLocation::class, 'sc_location_receiving_id');
    }

    public function toBranch(): BelongsTo
    {
        return $this->belongsTo(ScLocation::class, 'sc_location_repairing_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
