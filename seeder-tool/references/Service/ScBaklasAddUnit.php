<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScBaklasAddUnit extends Model
{
    use HasFactory;
    use SoftDeletes;
   
    protected $table = 'baklas_add_unit';
    protected $fillable = [
        'id',
        'warehouse_id',
        'reference_num',
        'remarks',
        'status',
        'processed_by',
        'processed_by_id',
        'processed_date'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = ScBaklasAddUnit::select('reference_num')->whereRaw('length(reference_num)=5')->max('reference_num');
            if (!empty($latest)) {
                $next_reference_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->reference_num = str_pad((string)$next_reference_num, 5, '0', STR_PAD_LEFT);
            } else {
                $model->reference_num = str_pad("1", 5, 0, STR_PAD_LEFT);
            }
        });
    }
}
