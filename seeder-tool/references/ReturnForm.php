<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ReturnForm extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'return_by_id',
        'remarks',
        'sample_form_id',
        'status',
        'return_approved_by_id',
        'return_date',
        'prepared_by_id',
        'return_form_num'
    ];
    protected $appends = [
        'salesman_name',
        'returned_by_name',
        'approve_by_name',
        'sample_form_num'
    ] ;

    public function return_form_items()
    {
        return $this->hasMany(ReturnFormItems::class, 'return_form_id');
    }

    public function return_form_files(){
        return $this->hasMany(ReturnFormFiles::class, 'return_form_id');
    }

    // public function getPrepareByNameAttribute()
    // {
    //     // Access the 'prepared_by_id' attribute of the current instance of the model
    //     $preparedById = $this->prepared_by_id;

    //     $preparedBy = User::find($preparedById);

    //     if ($preparedBy) {
    //         return $preparedBy->name;
    //     } else {
    //         return 'Unknown';
    //     }
    // }
    public function getReturnedByNameAttribute()
    {
        $requestedById = $this->return_by_id;

        $requestedBy = Salesman::find($requestedById);

        if ($requestedBy) {
            return $requestedBy->name;
        } else {
            return 'Unknown';
        }
    }
    public function getApproveByNameAttribute()
    {
        $approveById = $this->return_approved_by_id;

        $approveBy = User::find($approveById);

        if ($approveBy) {
            return $approveBy->name;
        } else {
            return 'Unknown';
        }
    }
    public function getSampleFormNumAttribute()
    {
        $sample_form_id = $this->sample_form_id;

        $sample_form = SampleForm::find($sample_form_id);

        if ($sample_form) {
            return $sample_form->sample_form_num;
        } else {
            return 'Unknown';
        }
    }

    public function getSalesmanNameAttribute()
    {
        $return_by_id = $this->return_by_id;

        $salesman = Salesman::find($return_by_id);

        if ($salesman) {
            return $salesman->name;
        } else {
            return 'Unknown';
        }
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'sample_form_selection',
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }
    // Return Status

    // 0 - Pending
    // 1 - Cancelled
    // 2 - Partially Return
    // 3 - Returned
}
