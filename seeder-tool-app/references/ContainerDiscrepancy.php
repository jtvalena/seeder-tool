<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ContainerDiscrepancy extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'container_id',
        'discrepancy_type_id',
        'discrepancy_category',
        'status',
        'created_by',
        'confirmed_by',
        'approved_by',
        'cancelled_by',
        'cancelled_reason',
        'remarks',
        'reference_doc',
        'warehouse_id',
    ];

    protected $guarded = [];

    public function container_discrepancy_files()
    {
        return $this->hasMany(ContainerDiscrepancyFile::class, 'container_discrepancy_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // $latest = PackingList::all()->last();
            $latest = ContainerDiscrepancy::select('container_discrepancy_num')->max('container_discrepancy_num');
            if (!empty($latest)) {
                $next_container_discrepancy_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->container_discrepancy_num = str_pad((string)$next_container_discrepancy_num, 5, '0', STR_PAD_LEFT);
            } else {
                $model->container_discrepancy_num = str_pad('1', 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
