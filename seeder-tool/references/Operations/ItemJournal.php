<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\User;
use App\Models\Operations\Warehouse;
use App\Models\Operations\CycleCount;
use App\Models\Operations\ItemJournalDetails;
class ItemJournal extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'cycle_count_id',
        'warehouse_id',
        'user_id',
        'date',
        'status',
        'remarks',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'item_journal_form_number'
    ];

    public function cycleCount()
    {
        return $this->belongsTo(CycleCount::class, 'cycle_count_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ItemJournalDetails::class, 'item_journal_id');
    }

    public function getItemJournalFormNumberAttribute()
    {
        $warehouseCode = strtoupper($this->warehouse->code);
        $number = str_pad($this->id,10, '0',STR_PAD_LEFT);

        return "IJF# {$warehouseCode}{$this->id}";
    }
}
