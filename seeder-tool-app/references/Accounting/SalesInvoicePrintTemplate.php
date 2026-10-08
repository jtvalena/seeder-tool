<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SalesInvoicePrintTemplate extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'size',
        'width',
        'height',
        'description',
        'max_items',
        'active',
        'print_offset_top',
        'print_offset_right',
        'print_offset_bottom',
        'print_offset_left',
        'print_offset_unit',
    ];

    protected $casts = [
        'width'              => 'decimal:2',
        'height'             => 'decimal:2',
        'active'             => 'boolean',
        'print_offset_top'   => 'decimal:4',
        'print_offset_right' => 'decimal:4',
        'print_offset_bottom'=> 'decimal:4',
        'print_offset_left'  => 'decimal:4',
    ];

    public function targets()
    {
        return $this->hasMany(SalesInvoicePrintTemplateTarget::class, 'print_template_id');
    }
}




