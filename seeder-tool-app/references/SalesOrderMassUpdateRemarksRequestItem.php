<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderMassUpdateRemarksRequestItem extends Model
{
    use HasFactory;

    protected $table = 'sales_order_mass_update_remarks_request_items';

    protected $fillable = [
        'so_mass_update_remarks_request_id',
        'sales_order_id',
        'old_remarks',
        'new_remarks',
        'update_mode',
        'add_position',
        'included_pl_ids',
        'update_status',
        'error_message',
    ];

    protected $casts = [
        'included_pl_ids' => 'array',
    ];

    public function salesOrderMassUpdateRemarksRequest()
    {
        return $this->belongsTo(SalesOrderMassUpdateRemarksRequest::class, 'so_mass_update_remarks_request_id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(Sales\SalesOrder::class);
    }
}
