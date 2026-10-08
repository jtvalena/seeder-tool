<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class BiometricStoreLog extends Model
{
    protected $table = 'biometric_store_logs';

    protected $fillable = [
        'store_branch_id',
        'employee_ids',
        'specific_date',
        'done_sync',
    ];

    protected $casts = [
        'employee_ids' => 'array',
        'done_sync'    => 'boolean',
    ];
}
