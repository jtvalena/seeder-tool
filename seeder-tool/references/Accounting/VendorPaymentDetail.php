<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\Bank;
use App\Models\Accounting\PaymentType;
use App\Models\Accounting\VendorPaymentAllocations;
use App\Traits\MorphMap;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class VendorPaymentDetail extends Model implements Auditable
{
    use SoftDeletes;
    use MorphMap;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['bank_name', 'payment_type_name','status_text','is_cvprint','is_pvprint'];

    protected $fillable = [
        'reference_number',
        'reused',
        'etb_id',
        'spr_id',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function vendor_payment_allocations()
    {
        return $this->hasMany(VendorPaymentAllocations::class, 'vendor_payment_detail_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = VendorPaymentDetail::select('reference_number')->whereRaw('length(reference_number)=7')->max('reference_number');
            if (!empty($latest)) {
                $next_reference_number = ($latest != null) ? intval($latest) + 1 : 1;
                $model->reference_number = str_pad((string)$next_reference_number, 7, '0', STR_PAD_LEFT);
            } else {
                $model->reference_number = str_pad('1', 7, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getBankNameAttribute() {
        return !!Bank::find($this->bank_id) ? Bank::find($this->bank_id)->name : '';
    }

    public function getPaymentTypeNameAttribute() {
        return !!PaymentType::find($this->payment_type_id) ? PaymentType::find($this->payment_type_id)->name : '';
    }

    public function getStatusTextAttribute()
    {
        if($this->status==0){
            return 'saved';
        }elseif($this->status==1){
            return 'submitted';
        }elseif($this->status==2){
            return 'approved';
        }elseif($this->status==3){
            return 'rejected';
        }elseif($this->status==4){
            return 'returned';
        }elseif($this->status==5){
            return 'bounced';
        }elseif($this->status==6){
            return 'cancelled';
        }elseif($this->status==7){
            return 'confirmed';
        }elseif($this->status==8){
            return 'exhausted';
        }elseif($this->status==9){
            return 'allocated';
        }elseif($this->status==10){
            return 'partial';
        }elseif($this->status==11){
            return 'replaced';
        }
    }
    
    public function getIsCvprintAttribute(){
        $isCVPrint = ChequeVoucher::where('id',$this->cheque_voucher_id)->value('printed');
        return $isCVPrint;
    }
    public function getIsPvprintAttribute(){
        $isPVPrint = ChequeVoucher::where('id',$this->cheque_voucher_id)->value('pv_print');
        return $isPVPrint;
    }
    public function cheque_voucher()
    {
        return $this->belongsTo(ChequeVoucher::class, 'cheque_voucher_id');
    }
}
