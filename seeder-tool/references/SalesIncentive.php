<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SalesIncentive extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'incentive_num',
        'sales_incentive_criteria_id',
        'year',
        'month',
        'period',
        'total_incentive_amount',
        'remarks',
        'status',
        'approved_by',
        'approved_at',
        'canceled_by',
        'canceled_at',
        'created_by',
        'updated_by',
    ];

    protected $appends = [
        'status_text',
    ];

    protected $casts = [
        'total_incentive_amount' => 'decimal:2',
    ];

    public function criteria()
    {
        return $this->belongsTo(SalesIncentiveCriteria::class, 'sales_incentive_criteria_id');
    }

    public function details()
    {
        return $this->hasMany(SalesIncentiveDetail::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = SalesIncentive::select('incentive_num')
                ->whereRaw('length(incentive_num)=6')
                ->max('incentive_num');

            if (!empty($latest)) {
                $next_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->incentive_num = str_pad((string)$next_num, 6, '0', STR_PAD_LEFT);
            } else {
                $model->incentive_num = str_pad('1', 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getStatusTextAttribute()
    {
        if ($this->status == 0) {
            return 'pending';
        } else if ($this->status == 1) {
            return 'for approval';
        } else if ($this->status == 3) {
            return 'approved';
        } else if ($this->status == 6) {
            return 'canceled';
        }
    }
}
