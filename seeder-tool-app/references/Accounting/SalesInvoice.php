<?php

namespace App\Models\Accounting;

use Illuminate\Support\Facades\DB;
use App\Models\MasterData\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\SalesInvoiceItem;
use App\Models\Accounting\SalesInvoicePrintTemplate;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class SalesInvoice extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    // Status constants
    const STATUS_PENDING      = 0;
    const STATUS_PRINTED      = 1;
    const STATUS_FOR_APPROVAL = 2;
    const STATUS_CANCELLED    = 3;

    protected $fillable = [
        'si_num',
        'customer_id',
        'invoice_date',
        'status',
        'total_amount',
        'bir_month',
        'remarks',
        'declared_percentage',
        'print_template_id',
        'print_values_only',
        'printed_at',
        'printed_by',
        'cancellation_reason',
        'cancelled_by',
        'cancelled_at',
        'reassignment_reason',
        'reassigned_by',
        'reassigned_at',
        'reassigned_to_si_id',
        'is_replacement',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    protected $appends = [
        'customer_name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->si_num)) {
                DB::transaction(function () use ($model) {
                    $latestSiNum = DB::table('sales_invoices')
                        ->lockForUpdate()
                        ->selectRaw('MAX(CAST(si_num AS UNSIGNED)) as max_num')
                        ->value('max_num');

                    $numericPart = !empty($latestSiNum) ? (int)$latestSiNum + 1 : 1;

                    $model->si_num = (string)$numericPart;
                });
            }

            if (empty($model->created_by)) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function (self $model) {
            if (empty($model->updated_by)) {
                $model->updated_by = Auth::id();
            }
        });
    }

    public function items()
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function printTemplate()
    {
        return $this->belongsTo(SalesInvoicePrintTemplate::class, 'print_template_id');
    }

    public function printedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'printed_by');
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->customer?->company_name ?? '';
    }
}




