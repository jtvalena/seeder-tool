<?php

declare(strict_types=1);

namespace App\Models\Service;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Service\ServiceOrderRequestOutbound;

class ServiceOrderRequestInbound extends Model
{
    use SoftDeletes;

    protected $table = 'service_order_request_inbound';

    protected $fillable = [
        'sor_ob_id',
        'inbound_num',
        'sc_location_receiving_id',
        'sc_location_repairing_id',
        'status',
        'user_id',
        'canceled_by',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    const STATUS_PENDING   = 0;
    const STATUS_CANCELLED = 1;

    /**
     * Computed effective status for the Inbound transfer.
     *
     * 0 = Pending     — no items physically received yet
     * 1 = In Progress — at least one item received, OR all items received but
     *                   the Ready-for-Pickup step has not been completed for every SOR
     * 2 = Done        — every IB item is received AND every associated SOR has
     *                   progressed through Ready-for-Pickup (SOR status ≥ 8)
     * 3 = Cancelled
     *
     * "Done" is intentionally distinct from the SOR's own "Completed" (5) so the
     * two lifecycles remain clearly separated.
     */
    public function getComputedStatusAttribute(): int
    {
        if ($this->status === self::STATUS_CANCELLED) {
            return 3;
        }

        if ($this->relationLoaded('items')) {
            $items = $this->items;
            if ($items->isNotEmpty()) {
                $total         = $items->count();
                $receivedCount = $items->where('received', true)->count();

                if ($receivedCount <= 0) {
                    return 0; // Pending
                }

                if ($receivedCount < $total) {
                    return 1; // In Progress — not all items received yet
                }

                // All items received — check whether Ready-for-Pickup has been
                // processed for every SOR.
                // STATUS_COMPLETED (5) = customer picked up at repairing branch.
                // STATUS_READY_FOR_PICKUP (8) = return-transfer OB created (awaiting return IB).
                // Both values mean the Ready-for-Pickup step is done for that SOR.
                $readyCount = $items->filter(function ($item) {
                    if (!$item->relationLoaded('sor') || !$item->sor) {
                        return false;
                    }
                    $s = (int) $item->sor->status;
                    return $s === 5 || $s >= 8; // COMPLETED or READY_FOR_PICKUP
                })->count();

                if ($readyCount >= $total) {
                    return 2; // Done — all received AND all SORs past Ready-for-Pickup
                }

                return 1; // In Progress — all received but Ready-for-Pickup pending for some
            }
        }

        return 0; // Pending
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->computed_status) {
            0       => 'Pending',
            1       => 'In Progress',
            2       => 'Done',
            3       => 'Cancelled',
            default => 'Unknown',
        };
    }

    protected $appends = ['status_text', 'computed_status'];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function items(): HasMany
    {
        return $this->hasMany(ServiceOrderRequestInboundItem::class, 'sor_ib_id');
    }

    public function receivingBranch(): BelongsTo
    {
        return $this->belongsTo(ScLocation::class, 'sc_location_receiving_id');
    }

    public function repairingBranch(): BelongsTo
    {
        return $this->belongsTo(ScLocation::class, 'sc_location_repairing_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function outbound(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderRequestOutbound::class, 'sor_ob_id');
    }

    // ── Auto-numbering ─────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model): void {
            if (empty($model->inbound_num)) {
                $latest = self::withTrashed()
                    ->selectRaw("MAX(CAST(inbound_num AS UNSIGNED)) as max_num")
                    ->value('max_num');
                $next = $latest ? (int) $latest + 1 : 1;
                $model->inbound_num = str_pad((string) $next, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
