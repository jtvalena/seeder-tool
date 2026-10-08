<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DispatchLoadingHistory extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'dispatch_loading_history';

    protected $fillable = [
        'dispatch_id',
        'packing_list_id',
        'wpf_id',
    ];
    // Relationships
    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class, 'dispatch_id');
    }

    public function dispatchItemPl()
    {
        return $this->belongsTo(DispatchItemPl::class, 'packing_list_id');
    }
}
