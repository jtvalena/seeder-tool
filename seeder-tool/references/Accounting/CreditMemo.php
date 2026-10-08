<?php

namespace App\Models\Accounting;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\CreditMemoFile;
use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;
use App\Models\Accounting\CreditMemoItem;
use App\Models\CreditMemoType;
use App\Models\Operations\Warehouse;
use App\Models\User;
use App\Models\MasterData\Customer;
use App\Models\Service\ScRepairForm;
use App\Models\Service\ScRepairFormFile;
use App\Traits\Caching;
use App\Models\Accounting\CreditMemoAllocation;

class CreditMemo extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;
    use MorphMap, HasAttributeChangesToBroadcast;

    protected $appends = [
        'morphMapKey',
        'status_text',
        'warehouse_name',
        'customer_company_name',
        'soa_month_year',
        'created_by_name',
        'updated_by_name',
        'deleted_by_name',
        'canceled_by_name',
        'acct_approved_by_name',
        'exec_approved_by_name',
        'credit_memo_type_by_name',
        'credit_memo_type_status',
        'repair_form_number',
        'files_count',
        'credit_memo_classification_id',
        'credit_memo_classification_name',
        'old_credit_memo_num'
    ];

    protected $fillable = [
        'credit_memo_num',
        'credit_memo_date',
        'credit_memo_type',
        'warehouse_id',
        'customer_id',
        'billing_address_id',
        'billing_address_text',
        'billing_contact_number',
        'billing_contact_person',
        'packing_list_nums',
        'customer_ref_no',
        'remarks',
        'status',
        'total_amount',
        'printed',
        'selected_month',
        'selected_year',
        'acct_approved_by',
        'acct_approved_date',
        'exec_approved_by',
        'exec_approved_date',
        'created_by',
        'updated_by',
        'deleted_by',
        'canceled_by',
        'cancel_date',
        'origin',
        'container_id',
        'total_cost',
        'remaining_amount',
        'allocated_amount',
    ];

    public function credit_memo_items()
    {
        return $this->hasMany(CreditMemoItem::class, 'credit_memo_id');
    }

    public function credit_memo_allocations()
    {
        return $this->hasMany(CreditMemoAllocation::class, 'credit_memo_id');
    }
    public function print_histories()
    {
        return $this->hasMany(CreditMemoPrintHistory::class, 'credit_memo_id');
    }

    public function warehouse_by_name()
    {
        // return $this->belongsTo(Warehouse::class, 'warehouse_id')->withTrashed();
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function customer()
    {
        // return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function created_by_user()
    {
        // return $this->belongsTo(User::class, 'created_by')->withTrashed();
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by_user()
    {
        // return $this->belongsTo(User::class, 'updated_by')->withTrashed();
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleted_by_user()
    {
        // return $this->belongsTo(User::class, 'deleted_by')->withTrashed();
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function canceled_by_user()
    {
        return $this->belongsTo(User::class, 'canceled_by');
    }

    public function acct_approved_by_user()
    {
        return $this->belongsTo(User::class, 'acct_approved_by');
    }

    public function exec_approved_by_user()
    {
        return $this->belongsTo(User::class, 'exec_approved_by');
    }

    public function credit_memo_types()
    {
        return $this->belongsTo(CreditMemoType::class,'credit_memo_type');
    }

    public function credit_memo_files()
    {
        return $this->hasMany(CreditMemoFile::class, 'credit_memo_id');
    }

    public function sc_repair_form_files()
    {
        return $this->hasMany(ScRepairFormFile::class, 'sc_repair_form_id', 'sc_repair_form_id');
    }

    public function sc_repair_form()
    {
        return $this->belongsTo(ScRepairForm::class);
    }

    /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'customer_with_cm_selection',
            'credit_memo_selection'
        ];
        static::creating(function ($model) use($cache_names) {
            $current_year = Carbon::now()->year;
            $latest = CreditMemo::whereRaw('left(credit_memo_num,4)="' . $current_year . '"')->max('credit_memo_num');
            if(!empty($latest)){
                $next_credit_memo_num = intval(substr($latest, -5)) + 1;
                $model->credit_memo_num = $current_year . '-' . str_pad((string)$next_credit_memo_num, 5, '0', STR_PAD_LEFT);
            }else{
                $model->credit_memo_num = $current_year . '-' . str_pad('1', 5, '0', STR_PAD_LEFT);
            }
            $model->forget($cache_names);
        });

        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));

        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }

    public function getWarehouseNameAttribute()
    {
        return $this->warehouse_by_name?->name ?? '';
    }

    public function getCustomerCompanyNameAttribute()
    {
        return $this->customer?->company_name ?? '';
    }

    public function getSoaMonthYearAttribute()
    {
        return $this->selected_year . '-' . str_pad($this->selected_month, 2, '0', STR_PAD_LEFT);
    }

    public function getCreatedByNameAttribute()
    {
        return $this->created_by_user?->name ?? '';
    }

    public function getUpdatedByNameAttribute()
    {
        return $this->updated_by_user?->name ?? '';
    }

    public function getDeletedByNameAttribute()
    {
        return $this->deleted_by_user?->name ?? '';
    }

    public function getCanceledByNameAttribute()
    {
        return $this->canceled_by_user?->name ?? '';
    }

    public function getAcctApprovedByNameAttribute()
    {
        return $this->acct_approved_by_user?->name ?? '';
    }

    public function getExecApprovedByNameAttribute()
    {
        return $this->exec_approved_by_user?->name ?? '';
    }

    public function getCreditMemoTypeByNameAttribute()
    {
        return $this->credit_memo_types?->name ?? '';
    }

    public function getCreditMemoTypeStatusAttribute()
    {
        return $this->credit_memo_types?->status ?? 0;
    }

    public function getRepairFormNumberAttribute()
    {
        return $this->sc_repair_form?->rf_number ?? '';
    }


    public function getFilesCountAttribute()
    {
        return $this->credit_memo_files->count() + $this->sc_repair_form_files->count();
    }

    public function getCreditMemoClassificationIdAttribute()
    {
        return $this->credit_memo_types?->credit_memo_classification_id ?? null;
    }

    public function getCreditMemoClassificationNameAttribute()
    {
        return $this->credit_memo_types?->classification?->name ?? '';
    }

    public function getOldCreditMemoNumAttribute()
    {
        if (empty($this->old_credit_memo_id)) {
            return '';
        }
        $old = CreditMemo::find($this->old_credit_memo_id);

        return $old ? $old->credit_memo_num : '';
    }

    public function getStatusTextAttribute()
    {
        $status_text = '';

        switch ($this->status) {
            case 0:
                $status_text = 'saved';
                break;
            case 1:
                $status_text = 'for accounting';
                break;
            case 2:
                $status_text = 'for exec';
                break;
            case 3:
                $status_text = 'approved';
                break;
            case 4:
                $status_text = 'completed';
                break;
            case 5:
                $status_text = 'rejected';
                break;
            case 6:
                $status_text = 'canceled';
                break;
            case 7:
                $status_text = 'deleted';
                break;
            case 8:
                $status_text = 'pending';
                break;
            case 9:
                $status_text = 'confirmed';
                break;
            case 10:
                $status_text = 'cancel soa balance';
                break;
            default:
                $status_text = '';
        }

        return $status_text;
    }
}
