<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileFromOtherModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_key',
        'chain_order',
        'step_order',
        'model',
        'function',
        'column',
        'value',
        'transaction_type',
        'final',
    ];
}
