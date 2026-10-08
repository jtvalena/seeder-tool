<?php

namespace App\Models\Sales;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackingListPrintHistory extends Model
{
    protected $fillable = [
        'packing_list_id',
        'user_id',
        'print_type',
        'printed_at',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
    ];

    public function packing_list(): BelongsTo
    {
        return $this->belongsTo(PackingList::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

