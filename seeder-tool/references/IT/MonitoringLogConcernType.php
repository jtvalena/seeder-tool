<?php

declare(strict_types=1);

namespace App\Models\IT;

use Illuminate\Database\Eloquent\Model;

class MonitoringLogConcernType extends Model
{
    protected $fillable = [
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
