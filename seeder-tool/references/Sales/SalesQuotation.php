<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MasterData\Customer;
use App\Models\Sales\Promo;
use App\Models\MasterData\Salesman;
use App\Models\Sales\SalesQuotationItem;
use App\Models\SalesQuotationMovement;
use App\Models\Service\ScRepairForm;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Auth;

class SalesQuotation extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['customer_name', 'salesman_name', 'issuer_name', 'status_text', 'override_by_name'];

    protected $fillable = [
        'quote_num',
        'customer_id',
        'salesman_id',
        'user_id',
        'remarks',
        'total_amount',
        'discounts',
        'terms',
        'convert_date',
        'status',
        'shipping_address_text',
        'shipping_address_id',
        'override_by',
        'discount_amount',
        'gross_amount',
        'submitted_by',
        'converted_by',
        'approved_by',
        'completed_by',
        'deleted_by',
        'submit_date',
        'approve_date',
        'complete_date',
        'promo_id',
        'special',
        'notes',
        'override_amount_approval',
        'on_queue',
        'item_type_id',
        'override_moq',
        'trade',
        'customer_order_id',
        'brand_id',
        'pullout_customer_order',
        'indent_order_id',
        'sc_repair_form_id',
    ];

    public function sales_quotation_items()
    {
        return $this->hasMany(SalesQuotationItem::class, 'sales_quotation_id');
    }

    public function customer()
    {
        // return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function promo()
    {
        // return $this->belongsTo(Promo::class, 'promo_id')->withTrashed();
        return $this->belongsTo(Promo::class, 'promo_id');
    }

    public function issuer()
    {
        // return $this->belongsTo(User::class, 'user_id')->withTrashed();
        return $this->belongsTo(User::class, 'user_id');
    }

    public function override_by_user()
    {
        // return $this->belongsTo(User::class, 'override_by')->withTrashed();
        return $this->belongsTo(User::class, 'override_by');
    }

    public function salesman()
    {
        // return $this->belongsTo(Salesman::class)->withTrashed();
        return $this->belongsTo(Salesman::class);
    }

    public function sc_repair_form()
    {
        return $this->belongsTo(ScRepairForm::class);
    }

    public function indent_order()
    {
        return $this->belongsTo(\App\Models\IndentOrder::class);
    }

    /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            SalesQuotationMovement::create([
                'sales_quotation_id' => $model->id,
                'status' => $model->status,
                'updated_by' => $model->user_id,
            ]);
        });

        static::updated(function ($model) {
            if($model->wasChanged('status')){
                SalesQuotationMovement::create([
                    'sales_quotation_id' => $model->id,
                    'status' => $model->status,
                    'updated_by' => $model->converted_by ?? Auth::id(),
                ]);
            }
        });
    //     static::creating(function ($model) {
    //         // $latest = SalesQuotation::all()->whereRaw('Right(quoteNum,2) <> "dm"')->last();
    //         $latest = SalesQuotation::select('quote_num')->whereRaw('length(quote_num)=6')->max('quote_num');
    //         if(!empty($latest)){
    //             $next_quote_num = ($latest != null) ? intval($latest) + 1: 1;
    //             $model->quote_num = str_pad((string)$next_quote_num, 6, '0', STR_PAD_LEFT);
    //         }else{
    //             $model->quote_num = str_pad('1', 6, '0', STR_PAD_LEFT);
    //         }

    //     });
    }

    public function getCustomerNameAttribute(){
        $customer_name = "";
        if(!empty($this->customer)){
            if($this->customer->type == 'bus'){
                $customer_name = !empty($this->customer->company_name) ? $this->customer->company_name: '';
            }else{
                $customer_name = !empty($this->customer->full_name) ? $this->customer->full_name: '';
            }
        }
        unset($this->customer);
        return $customer_name;
    }

    public function getSalesmanNameAttribute(){
        $salesman_name = !empty($this->salesman) ? $this->salesman->name : '';
        unset($this->salesman);
        return $salesman_name;
    }

    public function getIssuerNameAttribute(){
        $issuer_name = !empty($this->issuer) ? $this->issuer->name : '';
        unset($this->issuer);
        return $issuer_name;
    }

    public function getOverrideByNameAttribute(){
        $override_by_name = !empty($this->override_by_user) ? $this->override_by_user->name : '';
        unset($this->override_by_user);
        return $override_by_name;
    }

    public function getStatusTextAttribute()
    {
        /*
            0 = 'saved'
            1 = 'converted'
            2 = 'rejected'
            3 = 'canceled'
            4 = 'deleted'
        */

        if($this->status==0){
            return 'saved';
        }elseif($this->status==1){
            return 'converted';
        }elseif($this->status==2){
            return 'rejected';
        }elseif($this->status==3){
            return 'canceled';
        }elseif($this->status==4){
            return 'deleted';
        }elseif($this->status==5){
            return 'for approval';
        }elseif($this->status==6){
            return 'approved';
        }
    }
}