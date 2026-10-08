<?php

namespace App\Models;

use App\Models\MasterData\Salesman;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SalesmanMonthlyTarget extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }
}
