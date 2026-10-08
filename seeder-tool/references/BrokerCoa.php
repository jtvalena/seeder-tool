<?php

namespace App\Models;

use App\Models\MasterData\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class BrokerCoa extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'broker_id',
        'coa_id',
    ];

    public function broker()
    {
        return $this->belongsTo(Vendor::class, 'broker_id');
    }

    public function coa()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'coa_id');
    }
}


