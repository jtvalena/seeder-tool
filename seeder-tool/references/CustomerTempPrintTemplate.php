<?php

namespace App\Models;

use App\Models\Accounting\SalesInvoicePrintTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerTempPrintTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'customer_temp_print_templates';

    protected $fillable = [
        'customer_temp_id',
        'sales_invoice_print_template_id',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function customer_temp()
    {
        return $this->belongsTo(CustomerTemp::class);
    }

    public function print_template()
    {
        return $this->belongsTo(SalesInvoicePrintTemplate::class, 'sales_invoice_print_template_id');
    }
}
