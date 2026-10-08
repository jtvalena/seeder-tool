<?php

namespace App\Models;

use App\Models\MasterData\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PackingListRevert extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        'approved_by_name',
        'requested_by_name',
        'customer_by_name',
    ];

    public function packing_list_revert_items()
    {
        return $this->hasMany(PackingListRevertItem::class, 'packing_list_revert_id');
    }

    public function approved_by_user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function requested_by_user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function company_name()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function getApprovedByNameAttribute()
    {
        return $this->approved_by_user->name ?? '';
    }

    public function getRequestedByNameAttribute()
    {
        return $this->requested_by_user->name ?? '';
    }

    public function getCustomerByNameAttribute()
    {
        return $this->company_name->company_name ?? '';
    }
}
