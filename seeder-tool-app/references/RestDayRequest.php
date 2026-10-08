<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestDayRequest extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'series',
        'option',
        'user_id',
        'employee_id',
        'temporary',
        'day',
        'week_no',
        'year',
        'status',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'rejected_by',
        'rejected_date',
        'reject_reason',
        'date_from',
        'date_to',
        'time_in',
        'time_out',
        'remarks',
        'cancelled_rd_request_id',
        'cancelled_rd_date',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = Auth::user()->id;
        });
        static::created(function ($model) {
            DB::transaction(function () use ($model) {
                $latest = DB::table('rest_day_requests')->lockForUpdate()->max('series');

                if (!empty($latest)) {
                    $next_series = intval(substr($latest, -6)) + 1;
                    $model->series = str_pad((string)$next_series, 6, '0', STR_PAD_LEFT);
                } else {
                    $model->series = str_pad('1', 6, '0', STR_PAD_LEFT);
                }

                $model->save();
            });
        });
    }

    // Status
    // Saved - 1
    // Approved - 2
    // Confirmed - 3
    // Rejected - 4

    public function cancelledRestDayRequests()
    {
        return $this->hasMany(CancelledRestDayRequest::class, 'rest_day_request_id');
    }
}
