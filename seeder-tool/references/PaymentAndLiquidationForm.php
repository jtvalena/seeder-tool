<?php

namespace App\Models;

use App\Http\Controllers\SharedFunctionController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class PaymentAndLiquidationForm extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'vendor_id',
        'transact_type_id',
        'prepared_by',
        'payment_method',
        'status',
        'overdue',
        'remarks',
        'system_id',
        'employee_id',
        'filed',
        'date_filed',
    ];

    public function PaymentAndLiquidationFormItems()
    {
        return $this->hasMany(PaymentAndLiquidationFormItem::class, 'payment_and_liquidation_form_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Serialize number generation across concurrent requests using a named MySQL advisory lock.
            // GET_LOCK is session-scoped: only one session holds 'palf_txn_num' at a time,
            // preventing two requests from reading the same max serial and producing a duplicate.
            DB::select("SELECT GET_LOCK('palf_txn_num', 10)");
            try {
                $model->transaction_number = SharedFunctionController::getPaddedSeriesWithYear(
                    static::class,
                    'transaction_number',
                    4
                );
            } finally {
                DB::select("SELECT RELEASE_LOCK('palf_txn_num')");
            }
        });
    }
}
