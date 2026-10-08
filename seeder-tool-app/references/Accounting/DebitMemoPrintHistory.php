<?php

namespace App\Models\Accounting;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DebitMemoPrintHistory extends Model
{
    protected $fillable = [
        'debit_memo_id',
        'user_id',
        'print_type',
        'printed_at',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
    ];

    public function debit_memo(): BelongsTo
    {
        return $this->belongsTo(DebitMemo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
