<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\User;
use App\Models\CfTransmittalDetail;

class CfTransmittal extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'cf_transmittals';

    protected $fillable = [
        'series',
        'user_id',
        'print_type',
    ];

    public function user_id()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function cf_transmittal_details()
    {
        return $this->hasMany(CfTransmittalDetail::class, 'cf_transmittal_id');
    }
}
