<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledTransactionJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_journal_id',
        'cancel_reason',
        'cancel_type',
        'cancelled_by',
    ];
}
