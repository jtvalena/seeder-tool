<?php

declare(strict_types=1);

namespace App\Models\Service;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ServiceOrderRequest extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    // ── Workflow status codes ──────────────────────────────────────────────────
    const STATUS_FOR_JOB_CARD          = 0;
    const STATUS_FOR_REPAIR_ASSIGNMENT = 1;
    const STATUS_FOR_OUTBOUND          = 2;
    const STATUS_OUTBOUND              = 3;
    const STATUS_INBOUND               = 4;
    const STATUS_COMPLETED             = 5;
    const STATUS_CANCELLED             = 6;
    const STATUS_REFUNDED              = 7;
    const STATUS_READY_FOR_PICKUP      = 8;
    const STATUS_FOR_REPAIR_COMPLETION = 9;

    // ── Item status (repair outcome) ───────────────────────────────────────────
    const ITEM_STATUS_REPAIRED  = 'repaired';
    const ITEM_STATUS_REFUNDED  = 'refunded';
    const ITEM_STATUS_REPLACED  = 'replaced';

    protected $table = 'service_order_requests';

    protected $fillable = [
        // Grouping & numbering
        'transaction_num',
        'sor_num',

        // Customer info
        'customer_name',
        'phone_num',
        'email',
        'address',
        'company_name',

        // Branch assignment
        'sc_location_receiving_id',
        'sc_location_repairing_id',
        'repairing_branch_confirmed',
        'repairing_branch_transferred',

        // Item info (flat — no separate items table)
        'item_id',
        'serial_number',
        'item_photo_path',
        'pre_assessment',
        'warranty_qr_code',
        'details_needed',
        'notes',
        'assisted_by',

        // Repair workflow
        'job_card_num',
        'item_status',
        'repair_type',
        'replacement_serial_number',

        // Logistics
        'logistics_phase',
        'outbound_by',
        'outbound_at',
        'inbound_by',
        'inbound_at',

        // Workflow status
        'status',
        'repair_done',

        // Cancellation
        'cancellation_reason',
        'canceled_by',
        'canceled_at',
        'override_by',
    ];

    protected $appends = ['status_text'];

    protected $casts = [
        'status'                      => 'integer',
        'repair_done'                 => 'boolean',
        'repairing_branch_confirmed'  => 'boolean',
        'repairing_branch_transferred'=> 'boolean',
        'created_at'                  => 'datetime',
        'updated_at'                  => 'datetime',
        'canceled_at'                 => 'datetime',
        'outbound_at'                 => 'datetime',
        'inbound_at'                  => 'datetime',
    ];

    // ── Readable label for the numeric status code ────────────────────────────
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_FOR_JOB_CARD          => 'For Job Card Creation',
            self::STATUS_FOR_REPAIR_ASSIGNMENT => 'For Repair Assignment',
            self::STATUS_FOR_OUTBOUND          => 'For Outbound',
            self::STATUS_OUTBOUND              => 'Outbound Records',
            self::STATUS_INBOUND               => 'For Inbound',
            self::STATUS_COMPLETED             => 'Completed',
            self::STATUS_CANCELLED             => 'Cancelled',
            self::STATUS_REFUNDED              => 'Refunded',
            self::STATUS_READY_FOR_PICKUP      => 'Ready for Pickup',
            default                            => 'Unknown',
        };
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    /** The item being serviced. */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /** Branch where the customer physically dropped off the item. */
    public function receivingBranch(): BelongsTo
    {
        return $this->belongsTo(ScLocation::class, 'sc_location_receiving_id');
    }

    /** Branch performing the actual repair. */
    public function repairingBranch(): BelongsTo
    {
        return $this->belongsTo(ScLocation::class, 'sc_location_repairing_id');
    }

    /** Print history records for this SOR. */
    public function printHistories(): HasMany
    {
        return $this->hasMany(ServiceOrderRequestPrintHistory::class, 'sor_id');
    }

    /** Remarks/notes log entries for this SOR. */
    public function remarks(): HasMany
    {
        return $this->hasMany(ServiceOrderRequestRemark::class, 'sor_id')->latest();
    }

    /** Spare parts used during repair (repair_type = 'with_parts'). */
    public function parts(): HasMany
    {
        return $this->hasMany(ServiceOrderRequestPart::class, 'sor_id');
    }

    // ── Auto-numbering ─────────────────────────────────────────────────────────
    public static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model): void {
            // Auto-generate sor_num (globally unique, zero-padded 6-digit integer).
            $latest = self::withTrashed()
                ->selectRaw('MAX(CAST(sor_num AS UNSIGNED)) as max_num')
                ->value('max_num');

            $next           = $latest ? (int) $latest + 1 : 1;
            $model->sor_num = str_pad((string) $next, 6, '0', STR_PAD_LEFT);

            // Auto-generate transaction_num if not provided.
            if (empty($model->transaction_num)) {
                $latestTxn = self::withTrashed()
                    ->selectRaw('MAX(CAST(transaction_num AS UNSIGNED)) as max_txn')
                    ->value('max_txn');

                $nextTxn              = $latestTxn ? (int) $latestTxn + 1 : 1;
                $model->transaction_num = str_pad((string) $nextTxn, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
