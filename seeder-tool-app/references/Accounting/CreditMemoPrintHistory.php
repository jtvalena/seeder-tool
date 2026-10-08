<?php

namespace App\Models\Accounting;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditMemoPrintHistory extends Model
{
    protected $fillable = [
        'credit_memo_id',
        'user_id',
        'printed_at',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
    ];

    public function credit_memo(): BelongsTo
    {
        return $this->belongsTo(CreditMemo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
