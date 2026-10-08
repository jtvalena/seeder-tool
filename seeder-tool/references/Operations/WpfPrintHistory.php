<?php

namespace App\Models\Operations;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WpfPrintHistory extends Model
{
    protected $fillable = [
        'wpf_id',
        'user_id',
        'printed_at',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
    ];

    public function wpf(): BelongsTo
    {
        return $this->belongsTo(WarehousePreparationList::class, 'wpf_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
