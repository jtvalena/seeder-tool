<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithholdingTaxTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_interval_id',
        'compensation_from',
        'compensation_to',
        'tax_base_amount',
        'tax_rate',
    ];
}
