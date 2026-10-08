<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SampleForm extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'salesman_id',
        'remarks',
        'status',
        'prepared_by_id',
        'borrowed_date',
        'sample_form_num',
        'printed_qr',
        'printed_pdf',
        'confirmed_by_id',
        'confirmed_date'
    ];
    protected $appends = [
        'prepare_by_name',
        'requested_by_name',
        'approve_by_name',
        'salesman_name'
    ] ;
    public function sample_form_items()
    {
        return $this->hasMany(SampleFormItem::class, 'sample_form_id');
    }

    public function sample_form_files(){
        return $this->hasMany(SampleFormFiles::class,'sample_form_id');
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

    public function getPrepareByNameAttribute()
    {
        // Access the 'prepared_by_id' attribute of the current instance of the model
        $preparedById = $this->prepared_by_id;

        $preparedBy = User::find($preparedById);

        if ($preparedBy) {
            return $preparedBy->name;
        } else {
            return 'Unknown';
        }
    }
    public function getRequestedByNameAttribute()
    {
        $requestedById = $this->salesman_id;

        $requestedBy = Salesman::find($requestedById);

        if ($requestedBy) {
            return $requestedBy->name;
        } else {
            return 'Unknown';
        }
    }
    public function getApproveByNameAttribute()
    {
        $approveById = $this->approved_by_id;

        $approveBy = User::find($approveById);

        if ($approveBy) {
            return $approveBy->name;
        } else {
            return 'Unknown';
        }
    }
    public function getSalesmanNameAttribute()
    {
        $salesman_id = $this->salesman_id;
        $salesman = Salesman::find($salesman_id);

        if ($salesman) {
            return $salesman->name;
        } else {
            return 'Unknown';
        }
    }

    // SAMPLE_FORM_STATUS

    // 0 - Saved
    // 1 - Approved
    // 2 - Confirmed
    // 3 - Cancelled
}
