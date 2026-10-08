<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Items\Item;
use App\Models\Operations\ItemJournal;
class ItemJournalDetails extends Model
{
    protected $fillable = [
        'item_journal_id',
        'item_id',
        'cycle_count_status',
        'quantity',
        'subtract',
        'remarks'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'subtract' => 'boolean'
    ];

    public function itemJournal(): BelongsTo
    {
        return $this->belongsTo(ItemJournal::class, 'item_journal_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
