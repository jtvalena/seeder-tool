<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledAdminPurchaseOrderAmendment extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'admin_purchase_order_amendment_id',
        'reason',
        'action_type',
        'user_id',
        'action_date',
    ];

    public function adminPurchaseOrderAmendment()
    {
        return $this->belongsTo(AdminPurchaseOrderAmendment::class);
    }
}
