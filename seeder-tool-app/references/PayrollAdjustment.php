<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\User;

class PayrollAdjustment extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'series',
        'payroll_period_id',
        'employee_id',
        'status_id',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'created_by',
        'updated_by',
        'total_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:12',
    ];

    protected $appends = [
        'approved_by_name',
        'confirmed_by_name',
    ];

    public function payroll_adjustment_details(){
        return $this->hasMany(PayrollAdjustmentDetail::class, 'payroll_adjustment_id', 'id');
    }

    public function getApprovedByNameAttribute()
    {
        $approveById = $this->approved_by;

        $approveBy = User::find($approveById);

        if ($approveBy) {
            return $approveBy->name;
        } else {
            return '';
        }
    }

    public function getConfirmedByNameAttribute()
    {
        $confirmedById = $this->confirmed_by;

        $confirmedBy = User::find($confirmedById);

        if ($confirmedBy) {
            return $confirmedBy->name;
        } else {
            return '';
        }
    }
}
