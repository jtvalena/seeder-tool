<?php

declare(strict_types=1);

namespace App\Models\Service;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ServiceOrderRequestJobCardLog extends Model
{
    protected $table = 'sor_job_card_logs';

    protected $fillable = [
        'sor_id',
        'user_id',
        'prev_job_card_num',
        'reason',
    ];

    public function sor()
    {
        return $this->belongsTo(ServiceOrderRequest::class, 'sor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
