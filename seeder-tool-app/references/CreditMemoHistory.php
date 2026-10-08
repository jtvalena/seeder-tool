<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CreditMemoHistory extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $appends = [
        'approved_by_name',
        'requested_by_name',
    ];

    public function approved_by_user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function requested_by_user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function getApprovedByNameAttribute()
    {
        return $this->approved_by_user->name ?? '';
    }

    public function getRequestedByNameAttribute()
    {
        return $this->requested_by_user->name ?? '';
    }
}
