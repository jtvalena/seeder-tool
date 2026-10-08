<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerAllowanceDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_allowance_id',
        'employee_id',
        'employee_mano_id',
        'amount',
        'supervisor',
    ];

    protected $appends = [
        'mano'
    ];

    protected $casts = [
        'employee_id' => 'int',
    ];

    public function getManoAttribute(){
        $isMano = is_null($this->employee_id) ? 1 : 0;
        return $isMano;
    }

    public function container_allowance(){
        return $this->belongsTo(ContainerAllowance::class);
    }
}
