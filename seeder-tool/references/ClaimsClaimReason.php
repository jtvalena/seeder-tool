<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimsClaimReason extends Model
{
    protected $fillable = [
        'text',
        'value',
    ];
}
