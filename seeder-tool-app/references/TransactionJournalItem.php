<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TransactionJournalItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'transaction_journal_id',
        'coa_id',
        'particulars',
        'amount',
        'recognition_month',
        'department_id',
        'job_id'
    ];
}
