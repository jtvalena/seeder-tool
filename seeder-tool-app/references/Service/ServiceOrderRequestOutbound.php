<?php

declare(strict_types=1);

namespace App\Models\Service;

use App\Models\Service\ScLocation;
use App\Models\Service\ServiceOrderRequestInbound;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrderRequestOutbound extends Model
{
    use SoftDeletes;

    protected $table = 'service_order_request_outbound';

    protected $fillable = [
        'outbound_num',
        'sc_location_receiving_id',
        'sc_location_repairing_id',
        'status',
        'user_id',
        'canceled_by',
        'prev_ob_id',
        'prev_ib_id',
    ];

    protected $casts = [
        'status'    => 'integer',
        'confirmed' => 'boolean',
    ];

    // Stored status — only used for "Cancelled" tracking.
    // The effective/display status is derived from item confirmations via computed_status.
    const STATUS_PENDING   = 0;
    const STATUS_CANCELLED = 1;

    /**
     * Compute effective status from item confirmations.
     * 0 = Pending  (no items confirmed)
     * 1 = In Progress (some confirmed, not all)
     * 2 = Done  (all items confirmed)
     * 3 = Cancelled
     */
    public function getComputedStatusAttribute(): int
    {
        if ($this->status === self::STATUS_CANCELLED) {
            return 3;
        }

        if ($this->relationLoaded('items')) {
            $items = $this->items;
            if ($items->isNotEmpty()) {
                $confirmedCount = $items->where('confirmed', true)->count();
                if ($confirmedCount >= $items->count()) return 2; // Done
                if ($confirmedCount > 0)                 return 1; // In Progress
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
        return $this->hasMany(ServiceOrderRequestOutboundItem::class, 'sor_ob_id');
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

    /** Original Outbound this return-transfer originated from. */
    public function prevOutbound(): BelongsTo
    {
        return $this->belongsTo(self::class, 'prev_ob_id');
    }

    /** Inbound record whose receipt triggered this return-transfer Outbound. */
    public function prevInbound(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderRequestInbound::class, 'prev_ib_id');
    }

    // ── Auto-numbering ─────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model): void {
            if (empty($model->outbound_num)) {
                $latest = self::withTrashed()
                    ->selectRaw("MAX(CAST(outbound_num AS UNSIGNED)) as max_num")
                    ->value('max_num');
                $next = $latest ? (int) $latest + 1 : 1;
                $model->outbound_num = str_pad((string) $next, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
