<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequestVerificationCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'verification_code',
        'expiration_date'
    ];
}
