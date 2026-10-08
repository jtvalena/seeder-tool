<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemTemplateDetails extends Model
{
    protected $fillable = [
        'item_template_id',
        'item_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function itemJournal(): BelongsTo
    {
        return $this->belongsTo(ItemTemplate::class, 'item_template_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

}
