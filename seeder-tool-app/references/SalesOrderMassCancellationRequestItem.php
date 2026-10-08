<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderMassCancellationRequestItem extends Model
{
    use HasFactory;

    protected $table = 'sales_order_mass_cancellation_request_items';

    protected $fillable = [
        'so_mass_cancellation_request_id',
        'sales_order_id',
        'cancellation_status',
        'error_message',
    ];

    public function salesOrderMassCancellationRequest()
    {
        return $this->belongsTo(SalesOrderMassCancellationRequest::class, 'so_mass_cancellation_request_id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(Sales\SalesOrder::class);
    }
}
