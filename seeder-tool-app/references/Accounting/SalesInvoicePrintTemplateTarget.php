<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class SalesInvoicePrintTemplateTarget extends Model
{
    protected $table = 'sales_invoice_print_template_targets';

    protected $fillable = [
        'print_template_id',
        'year',
        'month',
        'target',
    ];

    protected $casts = [
        'year'   => 'integer',
        'month'  => 'integer',
        'target' => 'decimal:2',
    ];

    public function printTemplate()
    {
        return $this->belongsTo(SalesInvoicePrintTemplate::class, 'print_template_id');
    }
}
