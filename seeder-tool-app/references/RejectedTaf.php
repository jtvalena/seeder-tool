<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class RejectedTaf extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'taf_id',
        'cancel_reason',
        'cancelled_by',
    ];
    public function rejectedTafs()
    {
        return $this->belongsTo(TravelAuthorizationForm::class, 'taf_id');
    }
}
