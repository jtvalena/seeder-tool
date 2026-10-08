<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnterBillCapitalizedStoreBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'enter_bill_id',
        'company_id',
        'store_branch_id',
    ];
}
