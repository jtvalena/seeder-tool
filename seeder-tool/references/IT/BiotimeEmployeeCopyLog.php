<?php

namespace App\Models\IT;

use Illuminate\Database\Eloquent\Model;

class BiotimeEmployeeCopyLog extends Model
{
    protected $fillable = [
        'user_id',
        'source_connection',
        'target_connection',
        'emp_code',
        'source_employee_id',
        'target_employee_id',
        'status',
        'message',
        'copied_tables',
    ];

    protected $casts = [
        'copied_tables' => 'array',
    ];
}
