<?php

declare(strict_types=1);

namespace App\Models\IT;

use Illuminate\Database\Eloquent\Model;

class MonitoringLogStatus extends Model
{
    protected $fillable = [
        'name',
        'code',
        'color',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
