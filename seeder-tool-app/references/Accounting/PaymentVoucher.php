<?php

namespace App\Models\Accounting;

use App\Models\StoreBranch;
use App\Models\User;
// use App\Models\Accounting\PaymentVoucherType;
// use App\Models\Company;
// use App\Models\MasterData\Vendor;
use App\Models\PaymentVoucherBillItems;
use App\Models\PaymentVoucherDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Contracts\Auditable;

class PaymentVoucher extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'pv_num',
        'payment_voucher_type',
        'company_id',
        'store_branches_id',
        'vendor_id',
        'bill_due',
        'terms',
        'total_amount',
        'balance_amount',
        'gross_amount',
        'prepared_by',
        'status',
        'remarks',
        'for_exec',
        'transact_date',
        'released_date',
        'is_printed',
        'checked_date',
        'vendor_payment_item_id',
        'cancelled_by',
        'cancelled_by',
        'deleted_by',
        'rejected_by',
        'movement_date',
        'for_asl',
        'exec_by',
        'asl_by',
        'liquidated_by',
        'asl_date',
        'liquidated_date',
        'system_id',
        'cleared_date',
        'cleared_by',
    ];

    protected $appends = [
        // 'prepared_by_name',
        // 'checked_by_name',
        // 'approved_by_name',
        // 'certified_by_name',
        'status_text',
        // 'type_text',
        // 'vendor_name',
        // 'released_by_name',
        // 'has_po',
        // 'company_name',
        // 'store_branch_name',
        'loadingForReceive'
    ];


    public function prepared_by_user()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }
    public function checked_by_user()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function approved_by_user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function certified_by_user()
    {
        return $this->belongsTo(User::class, 'certified_by');
    }

    public function released_by_user()
    {
        return $this->belongsTo(User::class, 'released_by');
    }


    // public function vendor_by_name()
    // {
    //     return $this->belongsTo(Vendor::class, 'vendor_id');
    // }

    // public function type_by_name()
    // {
    //     return $this->belongsTo(PaymentVoucherType::class, 'payment_voucher_type');
    // }
    // public function company()
    // {
    //     return $this->belongsTo(Company::class, 'company_id');
    // }
    public function store_branch()
    {
        return $this->belongsTo(StoreBranch::class, 'store_branches_id');
    }

    // public function payment_voucher_items()
    // {
    //     return $this->hasMany(PaymentVoucherItem::class, 'payment_voucher_id');
    // }

    public function payment_voucher_details()
    {
        return $this->hasMany(PaymentVoucherDetail::class, 'payment_voucher_id')
            ->orderByRaw('CASE WHEN sequence is NULL THEN id ELSE sequence END');
    }

    // public function getApprovedByNameAttribute(){
    //     $name = !empty($this->approved_by_user) ? $this->approved_by_user->name : '';
    //     unset($this->approved_by_user);
    //     return $name;
    // }

    // public function getCertifiedByNameAttribute(){
    //     $name = !empty($this->certified_by_user) ? $this->certified_by_user->name : '';
    //     unset($this->certified_by_user);
    //     return $name;
    // }

    // public function getReleasedByNameAttribute(){
    //     $name = !empty($this->released_by_user) ? $this->released_by_user->name : '';
    //     unset($this->released_by_user);
    //     return $name;
    // }

    // public function getPreparedByNameAttribute(){
    //     $name = !empty($this->prepared_by_user) ? $this->prepared_by_user->name : '';
    //     unset($this->prepared_by_user);
    //     return $name;
    // }

    // public function getCheckedByNameAttribute(){
    //     $name = !empty($this->checked_by_user) ? $this->checked_by_user->name : '';
    //     unset($this->checked_by_user);
    //     return $name;
    // }

    // public function getTypeTextAttribute(){
    //     $code = !empty($this->type_by_name) ? $this->type_by_name->code : '';
    //     unset($this->type_by_name);
    //     return $code;
    // }

    // public function getHasPoAttribute(){
    //     $has_po = !empty($this->type_by_name) ? $this->type_by_name->has_po : '';
    //     unset($this->type_by_name);
    //     return $has_po;
    // }

    // public function getVendorNameAttribute() {
    //     if ($this->vendor_by_name) {
    //         $code = !empty($this->vendor_by_name->company_name) ? $this->vendor_by_name->company_name : '';
    //         unset($this->vendor_by_name);
    //         return $code;
    //     }
    // }
    public function storeBranch()
    {
        return $this->belongsTo(StoreBranch::class, 'store_branches_id', 'id');
    }
    // public function getCompanyNameAttribute()
    // {
    //     return $this->company ? $this->company->registered_name : null;
    // }

    public function getStoreBranchNameAttribute()
    {
        return $this->store_branch ? $this->store_branch->name : null;
    }

    public function getStatusTextAttribute()
    {
        if($this->status==1){
            return 'for checking';
        }elseif($this->status==2){
            return 'checking';
        }elseif($this->status==3){
            return 'checked';
        }elseif($this->status==4){
            return 'releasing';
        }elseif($this->status==5){
            return 'released';
        }elseif($this->status==6){
            return 'cancelled';
        }elseif($this->status==7){
            return 'rejected';
        }
    }

    public function getLoadingForReceiveAttribute(){
        return 0;
    }

    public static function boot()
    {
        parent::boot();

        $cache_names = [
            'find_enter_bill',
        ];

        static::creating(function ($model) use($cache_names) {
            $now = Carbon::now();
            $yearMonth = $now->format('Y-m');
            $latest = PaymentVoucher::select('apv_num')->where('apv_num', 'like', "{$yearMonth}-%")->orderBy('apv_num', 'desc')->first();
            if(!empty($latest)){
                $lastNumber = (int)substr($latest->apv_num, -4);
                $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }else{
                $nextNumber = '0001';
                $model->apv_num = $nextNumber;
            }
            $model->apv_num = "{$yearMonth}-{$nextNumber}";

            //$model->forget($cache_names);
            foreach($cache_names as $cache_name){
                Cache::forget($cache_name);
            }
        });

        static::created(function($model){
            $pv_num_exists = PaymentVoucher::where('pv_num', $model->pv_num)->count();
            if($pv_num_exists > 1) {
                $last_number = (int)substr($model->pv_num, -6);
                $next_number = str_pad($last_number + 1, 6, '0', STR_PAD_LEFT);
                $new_pv_num = substr($model->pv_num, 0, -6) . $next_number;
                $model->pv_num = $new_pv_num;
                $model->save();
            } else {
                $model->pv_num;
            }
        });

        static::updated((function ($model) use($cache_names) {
            foreach($cache_names as $cache_name){
                Cache::forget($cache_name);
            }
        }));
        static::deleted((function ($model) use($cache_names) {
            foreach($cache_names as $cache_name){
                Cache::forget($cache_name);
            }
        }));
    }

    public function PaymentBillItems()
    {
        return $this->hasMany(PaymentVoucherBillItems::class,'payment_voucher_id')
            ->leftJoin('enterbill_items', 'payment_voucher_bill_items.enter_bill_ids','enterbill_items.enter_bill_ids')
            ->select(
                'payment_voucher_bill_items.enter_bill_ids',
                'enterbill_items.transact_id'
            )
            ->groupBy('enterbill_items.transact_id');
    }
    public function vendor_payment_details()
    {
        return $this->hasMany(VendorPaymentDetail::class, 'payment_voucher_ids');
    }
}
