<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MasterData\Salesman;

class SalesmanPosition extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'title',
        'description',
        'code',
    ];

    public function salesmans() {
        return $this->hasMany(Salesman::class, 'salesman_position_id');
    }
}
