<?php

namespace App\Models\Accounting;

use App\Models\Accounting\CreditMemo;
use App\Models\Accounting\DebitMemo;
use App\Models\MasterData\Customer;
use App\Models\StoreStockTransferFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class StoreStockTransfer extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'series',
        'source_customer_id',
        'destination_customer_id',
        'transfer_date',
        'soa_month',
        'soa_year',
        'remarks',
        'status',
        'revert_reason',
        'revise_reason',
        'total_amount',
        'credit_memo_id',
        'debit_memo_id',
        'created_by',
        'updated_by',
        'approved_by',
        'approved_at',
        'customer_ref_no',
        'packing_list_nums',
    ];

    protected $appends = [
        'source_customer_name',
        'destination_customer_name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (self $model) {
            if (!empty($model->series)) {
                return;
            }

            DB::transaction(function () use ($model) {
                $latestSeries = DB::table('store_stock_transfers')
                    ->lockForUpdate()
                    ->max('series');

                if (!empty($latestSeries)) {
                    $nextSeries = (int)substr($latestSeries, -6) + 1;
                } else {
                    $nextSeries = 1;
                }

                $model->series = str_pad((string)$nextSeries, 6, '0', STR_PAD_LEFT);
                $model->saveQuietly();
            });
        });
    }

    public function items()
    {
        return $this->hasMany(StoreStockTransferItem::class);
    }

    public function sourceCustomer()
    {
        return $this->belongsTo(Customer::class, 'source_customer_id');
    }

    public function destinationCustomer()
    {
        return $this->belongsTo(Customer::class, 'destination_customer_id');
    }

    public function creditMemo()
    {
        return $this->belongsTo(CreditMemo::class, 'credit_memo_id', 'id');
    }

    public function debitMemo()
    {
        return $this->belongsTo(DebitMemo::class, 'debit_memo_id', 'id');
    }

    public function getStatusTextAttribute(): string
    {
        return match ((int)$this->status) {
            0 => 'Pending',
            1 => 'For Approval',
            2 => 'Approved',
            3 => 'Revise',
            default => '',
        };
    }

    public function getSourceCustomerNameAttribute(): string
    {
        return $this->sourceCustomer?->company_name ?? '';
    }

    public function getDestinationCustomerNameAttribute(): string
    {
        return $this->destinationCustomer?->company_name ?? '';
    }

    public function files()
    {
        return $this->hasMany(StoreStockTransferFile::class);
    }
}

