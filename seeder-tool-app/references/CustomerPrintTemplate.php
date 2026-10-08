<?php

namespace App\Models;

use App\Models\Accounting\SalesInvoicePrintTemplate;
use App\Models\MasterData\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPrintTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'customer_print_templates';

    protected $fillable = [
        'customer_id',
        'sales_invoice_print_template_id',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function print_template()
    {
        return $this->belongsTo(SalesInvoicePrintTemplate::class, 'sales_invoice_print_template_id');
    }
}
