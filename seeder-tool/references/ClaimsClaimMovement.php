<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimsClaimMovement extends Model
{
    protected $table = 'claims_claim_movement';

    protected $fillable = [
        'claims_claim_id',
        'action',
        'from_status',
        'to_status',
        'from_printed',
        'to_printed',
        'credit_memo_id',
        'reason',
        'performed_by',
    ];

    public function claim()
    {
        return $this->belongsTo(ClaimsClaim::class, 'claims_claim_id');
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
