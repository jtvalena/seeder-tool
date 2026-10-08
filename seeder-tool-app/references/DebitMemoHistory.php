<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class DebitMemoHistory extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $appends = [
        'approved_by_name',
        'requested_by_name',
    ];

    protected $fillable = [
        'debit_memo_id',
        'old_year',
        'old_month',
        'new_year',
        'new_month',
        'soa_id',
        'requested_by',
        'approved_by',
        'approved_date',
        'reason',
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
