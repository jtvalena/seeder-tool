<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\EnterBillItems;
use App\Models\Operations\TransactType;
use App\Models\ServicePaymentRequest;
class EnterBill extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'enter_bill';
    protected $fillable = [
        'company_id',
        'store_branch_id',
        'transact_type_id',
        'vendor_id',
        'bill_date',
        'prepared_by',
        'updated_by',
        'deleted_by',
        'amount_due',
        'status',
        'deleted_at',
        'bill_date',
        'remarks',
        'system_id',
        'is_transmitted',
        'mass_creation',
        'overdue',
        'subject_for_atd',
        'released_date',
        'days_after_release',
        'mark_as_completed_by',
        'mark_as_completed_date',
    ];

    public function prf_item()
    {
        return $this->belongsTo(EnterBillItems::class,'id');
    }

    public function enterBillItems()
    {
        return $this->hasMany(EnterBillItems::class, 'enter_bill_ids', 'id');
    }

    public static function boot()
    {
        parent::boot();
        
        static::updating(function($model) {
            if($model->isDirty('status')) {
                if(in_array($model->status, [5,6])) {
                    $eb_items = EnterBillItems::where('enter_bill_ids', $model->id)->first();
                    $transact_type = TransactType::where('id', $eb_items->transact_type_id)->first();
                    switch($transact_type->model_name) {
                        case 'ServicePaymentRequest':
                            $spr = ServicePaymentRequest::where('id', $eb_items->transact_id)->first();
                            $spr->status = 6;
                            $spr->save();
                            break;
                        default:
                            return;
                    }    
                }
            }
            // Set released_date when status is moved to Completed (2).
            // Do NOT call $model->save() here — we are inside the 'updating' event;
            // attributes set on $model are already included in the pending UPDATE statement.
            if($model->status == 2 && !$model->released_date){
                $model->released_date = now();
            }

            // Set subject_for_atd when more than 7 non-Sunday days have elapsed since release
            if ($model->released_date) {
                $daysSinceRelease = self::countDaysExcludingSundays(
                    Carbon::parse($model->released_date),
                    Carbon::now()
                );

                if ($daysSinceRelease > 7) {
                    $model->subject_for_atd = 1;
                }
            }

            // Always keep days_after_release current whenever the record is updated
            if ($model->released_date) {
                $model->days_after_release = self::countDaysExcludingSundays(
                    Carbon::parse($model->released_date),
                    Carbon::now()
                );
            }
        });
    }

    /**
     * Count calendar days from $from up to (but not including) $to, skipping Sundays.
     */
    private static function countDaysExcludingSundays(Carbon $from, Carbon $to): int
    {
        $days    = 0;
        $current = $from->copy()->startOfDay();
        $end     = $to->copy()->startOfDay();

        while ($current->lt($end)) {
            if ($current->dayOfWeek !== Carbon::SUNDAY) {
                $days++;
            }
            $current->addDay();
        }

        return $days;
    }
}
