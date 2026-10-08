<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VendorCoaItems extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'coa_id',
        'particulars',
        'debit_amount',
        'credit_amount',
        'recognition_month',
        'department_id',
        'job_id'];
}
