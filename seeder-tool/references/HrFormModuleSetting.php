<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\User;

class HrFormModuleSetting extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'payroll_period_id',
        'submission_deadline',
        'module_name',
        'updated_by',
    ];

    protected $casts = [
        'submission_deadline' => 'datetime',
    ];

    public function payroll_period()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getUpdatedByNameAttribute()
    {
        return $this->updated_by_user->name ?? null;
    }
}
