<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\MasterData\Salesman;
use App\Models\StoreBranch;
use Illuminate\Support\Facades\Cache;
use App\Models\CustomerClassification;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterData\CustomerCategory;
use App\Traits\MorphMap;

use App\Models\CustomerTempPrintTemplate;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerTemp extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use MorphMap;

    protected $table = 'customer_temp';

    protected $appends = [
        'full_name',
        'all_shipping_addresses',
        'salesman_name',
        'customer_name',
        'customer_affiliate_name',
        'customer_category_name',
        'customer_classification_name',
        'morphMapKey',
        'customer_code',
        'sales_coordinator_name',
        'customer_code_name',
        'user_name',
        'store_branch_name',
    ];

    protected $fillable = [
        'id',
        'code',
        'type',
        'company_name',
        'first_name',
        'middle_name',
        'last_name',
        'shipping_addresses',
        'billing_addresses',
        'delivery_instructions',
        'shipping_via',
        // 'tel_1',
        // 'tel_2',
        // 'tel_3',
        // 'fax',
        // 'mobile_1',
        // 'mobile_2',
        // 'mobile_3',
        // 'email',
        'terms',
        'salesman_on_creation',
        'salesman_id',
        'contact_persons',
        'valid_ids',
        'remarks',
        'special',
        'vip',
        'vat_rate',
        'discounts',
        'active',
        'credit_limit',
        'approved_by_sales',
        'approved_by_acct',
        'approved_by_exec',
        'other_contacts',
        'store',
        'inrs_name',
        'print_no_price',
        'affiliate_customer_id',
        'customer_category_id',
        'classification_id',
        'sales_target_amount',
        'recycle_so',
        'auto_cancel_sales_order_on_dispatch',
        'is_mother_customer',
        'sales_coordinator_id',
        'store_branch_id',
        'action',
        'customer_id',
        'approved_by',
        'approval_date',
        'customer_code_id',
        'user_id',
        'trade',
        'hold',
        'accounting_remarks',
        'si_creation_notification',
    ];

    public function salesman_on_creation()
    {
        return $this->belongsTo(Salesman::class, 'salesman_on_creation');
    }

    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }

    public function customer_category()
    {
        return $this->belongsTo(CustomerCategory::class);
    }

    public function customer_classification()
    {
        return $this->belongsTo(CustomerClassification::class, 'classification_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = "INGCOC" . date('jnGi');
        });

        static::created((function ($model) {
            Cache::forget('customers_temp_dropdown');
            Cache::forget('customers_service_dropdown');
        }));
        static::updated((function ($model) {
            Cache::forget('customers_temp_dropdown');
            Cache::forget('customers_service_dropdown');
        }));
        static::deleted((function ($model) {
            Cache::forget('customers_temp_dropdown');
            Cache::forget('customers_service_dropdown');
        }));
    }

    public function getFullNameAttribute()
    {
        $full_name = $this->last_name;

        if (!empty($this->first_name)) {
            $full_name .= ', ' . $this->first_name;
        }

        if (!empty($this->middle_name)) {
            $full_name .= ' ' . $this->middle_name;
        }

        return $full_name;
    }

    public function getCustomerNameAttribute()
    {
        $full_name = "";

        if ($this->type == 'bus') {
            $full_name = $this->company_name;
        } else {
            $full_name = $this->full_name;
        }

        return $full_name;
    }

    public function getAllShippingAddressesAttribute()
    {

        $address_options = [];
        // $shipping_addresses = !empty($this->shipping_addresses) ? json_decode($this->shipping_addresses, TRUE) : "";
        $shipping_addresses = DB::table('customer_temp_addresses')
        ->select('address','cities.citymunDesc as city','countries.id as country_id')
        ->join('countries','countries.country_code','=','customer_temp_addresses.country_code')
        ->join('cities','cities.citymunCode','=','customer_temp_addresses.city_code')
        ->where('customer_temp_id',$this->id)
        ->where('type_address','shipping')->get();
        // if (!empty($shipping_addresses)) {

            foreach ($shipping_addresses as $key => $shipping_address) {
                $country =  DB::table('countries')->select('iso_3166_2')->where('country_code', $shipping_address->country_id)->first();
                $address =  !empty($shipping_address->address) ? $shipping_address->address . ', ' : "";
                $city    =  !empty($shipping_address->city) ? $shipping_address->city . ', ' : "";

                $address_options[$key]['value'] = $key;
                $address_options[$key]['text'] = $address . $city . $country->iso_3166_2;
            }
        // }

        return $address_options;
    }

    public function getSalesmanNameAttribute()
    {
        $salesman_name = !empty($this->salesman) ? $this->salesman->name : '';
        unset($this->salesman);
        return $salesman_name;
    }

    public function getAddressAttribute()
    {
        $address = '';
        $shipping_addresses = !empty($this->shipping_addresses) ? json_decode($this->shipping_addresses, TRUE) : "";
        if (!empty($shipping_addresses)) {
            $address = $shipping_addresses[0]['address'] . ' ' . $shipping_addresses[0]['city'];
        }
        return $address;
    }

    public function getCustomerAffiliateNameAttribute()
    {
        return !!$this->affiliate_customer_id  === null? '' : $this->company_name;
    }

    public function getCustomerCategoryNameAttribute()
    {
        return !empty($this->customer_category) ? $this->customer_category->name : '';
    }

    public function getCustomerClassificationNameAttribute()
    {
        return $this->classification_id === null || $this->classification_id === 0 || $this->is_mother_customer ? '' : $this->customer_classification->name;
    }

    public function getCustomerCodeAttribute()
    {
        $data = CustomerCode::find($this->customer_code_id);

        return !!$data ? $data : '';
    }

    public function customer_files()
    {
        return $this->hasMany(CustomerTempFile::class, 'customer_temp_id');
    }

    public function getSalesCoordinatorNameAttribute()
    {
        return $this->sales_coordinator_id ? Salesman::getQuery()->where('id', $this->sales_coordinator_id)->value('name') : '' ;
    }

    public function getCustomerCodeNameAttribute()
    {
        return $this->customer_code_id ? CustomerCode::getQuery()->where('id', $this->customer_code_id)->value('name') : '' ;
    }

    public function getStoreBranchNameAttribute()
    {
        return $this->store_branch_id ? StoreBranch::getQuery()->where('id', $this->store_branch_id)->value('name') : '';
    }

    public function customer_shipping_address()
    {
        return $this->hasMany(CustomerTempAddress::class,'customer_temp_id')->where('type_address', 'shipping') ?? [];
    }

    public function customer_billing_address()
    {
        return $this->hasMany(CustomerTempAddress::class,'customer_temp_id')->where('type_address', 'billing') ?? [];
    }

    public function clusters()
    {
        return $this->hasManyThrough(Cluster::class, CustomerTempCluster::class, 'customer_temp_id', 'id', 'id', 'cluster_id')
            ->distinct('clusters.id');
    }
    public function sub_clusters()
    {
        return $this->hasManyThrough(SubCluster::class, CustomerTempCluster::class, 'customer_temp_id', 'id', 'id', 'sub_cluster_id');
    }

    public function getUserNameAttribute(){
        if(!is_null($this->user_id)){
            $user = User::find($this->user_id);
            return isset($user) ? $user->name : '';
        }
        return '';
    }

    public function customer_temp_print_templates()
    {
        return $this->hasMany(CustomerTempPrintTemplate::class, 'customer_temp_id')->with('print_template');
    }

    public function default_print_template()
    {
        return $this->hasOne(CustomerTempPrintTemplate::class, 'customer_temp_id')->where('is_default', 1)->with('print_template');
    }
}
