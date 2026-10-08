<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class SalesInvoicePrintHistory extends Model
{
    protected $fillable = [
        'sales_invoice_id',
        'print_template_id',
        'printed_by',
        'is_override',
    ];

    protected $casts = [
        'is_override' => 'boolean',
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function printTemplate()
    {
        return $this->belongsTo(SalesInvoicePrintTemplate::class, 'print_template_id');
    }

    public function printedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'printed_by');
    }
}
