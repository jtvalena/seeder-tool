<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRequestTransactionItem extends Model
{
    use HasFactory;
    protected $fillable = ['request_transaction_type_id','transaction_id','request_trasaction_id'];
}
