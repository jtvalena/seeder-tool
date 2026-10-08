<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'status_id',
        'container_date',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'time_started',
        'time_ended',
        'payroll_period_id',
    ];

    protected $appends = [
        'employee_count',
        'approved_by_name',
        'confirmed_by_name',
    ];

    public function container_allowance_details(){
        return $this->hasMany(ContainerAllowanceDetails::class, 'container_allowance_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $latest = ContainerAllowance::max('container_allowance_num');
            if(!empty($latest)){
                $next_container_allowance_num = intval(substr($latest, -5)) + 1;
                $model->container_allowance_num = str_pad((string)$next_container_allowance_num, 5, '0', STR_PAD_LEFT);
            }else{
                $model->container_allowance_num = str_pad('1', 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getEmployeeCountAttribute(){
        $count = ContainerAllowanceDetails::where('container_allowance_id', $this->id)->count();

        return $count;
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
