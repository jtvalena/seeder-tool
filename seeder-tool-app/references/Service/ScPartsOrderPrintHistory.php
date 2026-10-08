<?php

declare(strict_types=1);

namespace App\Models\Service;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScPartsOrderPrintHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'printed_by',
        'sc_parts_order_id',
        'sc_repair_form_id',
        'print_type',
        'printed_from',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function sc_parts_order(): BelongsTo
    {
        return $this->belongsTo(ScPartsOrder::class);
    }

    public function sc_repair_form(): BelongsTo
    {
        return $this->belongsTo(ScRepairForm::class);
    }

    public function printedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'printed_by');
    }
}
