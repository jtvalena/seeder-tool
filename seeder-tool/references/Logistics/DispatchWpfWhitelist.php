<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;

class DispatchWpfWhitelist extends Model
{
    protected $table = 'dispatch_wpf_whitelist';

    protected $fillable = [
        'wpf_id',
        'reason',
        'created_by',
    ];

    public function wpf()
    {
        return $this->belongsTo(\App\Models\Operations\WarehousePreparationList::class, 'wpf_id');
    }
}
