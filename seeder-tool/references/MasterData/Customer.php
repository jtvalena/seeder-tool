<?php

namespace App\Models\MasterData;

use App\Models\Accounting\StatementOfAccount;
use App\Models\Cluster;
use App\Models\CustomerAddress;
use App\Models\MasterData\CustomerCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\MorphMap;
use App\Models\MasterData\Salesman;
use App\Models\Sales\SalesOrder;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\DB;
use App\Models\Service\ScDelivery;
use App\Models\CustomerClassification;
use App\Models\CustomerCluster;
use App\Models\CustomerCode;
use App\Models\CustomerFile;
use App\Models\StoreBranch;
use App\Models\Sales\SalesOrderItem;
use App\Models\Service\ScReceivingReport;
use App\Models\SubCluster;
use App\Models\CustomerPrintTemplate;
use App\Models\UserCustomerAccess;
use App\Traits\Caching;

class Customer extends Model implements Auditable
{
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;
    use MorphMap;

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
        'print_with_price',
        'print_no_price',
        'print_srp',
        'affiliate_customer_id',
        'customer_category_id',
        'classification_id',
        'sales_target_amount',
        'recycle_so',
        'auto_cancel_sales_order_on_dispatch',
        'is_mother_customer',
        'sales_coordinator_id',
        'store_branch_id',
        'customer_code_id',
        'accounting_remarks',
        'override_moq',
        'trade',
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

    public function sales_orders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
    }

    public function sales_order_items()
    {
        return $this->hasManyThrough(SalesOrderItem::class, SalesOrder::class, 'customer_id', 'sales_order_id');
    }

    public function sc_receiving_reports(){
        return $this->hasMany(ScReceivingReport::class, 'customer_id');
    }

    public function sc_deliveries(){
        return $this->hasMany(ScDelivery::class, 'customer_id');
    }

    public function affiliate_customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customer_category()
    {
        return $this->belongsTo(CustomerCategory::class);
    }

    public function customer_classification()
    {
        return $this->belongsTo(CustomerClassification::class, 'classification_id', 'id');
    }

    public function statement_of_accounts()
    {
        return $this->hasMany(StatementOfAccount::class);
    }

    public function clusters()
    {
        return $this->hasManyThrough(Cluster::class, CustomerCluster::class, 'customer_id', 'id', 'id', 'cluster_id')
        ->distinct('clusters.id');
    }

    public function sub_clusters()
    {
        return $this->hasManyThrough(SubCluster::class, CustomerCluster::class, 'customer_id', 'id', 'id', 'sub_cluster_id');
    }

    public function users_include()
    {
        return $this->hasMany(UserCustomerAccess::class)->where('exclude', 0);
    }

    public function users_exclude()
    {
        return $this->hasMany(UserCustomerAccess::class)->where('exclude', 1);
    }

    /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = "INGCOC" . date('jnGi');
        });

        $cache_names = [
            'customers_dropdown',
            'customers_service_dropdown',
            'service_end_user_customer',
            'customer_selection',
            'customer_selection_v3',
            'customer_selection_v4',
        ];

        static::created((function ($model) use ($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use ($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use ($cache_names) {
            $model->forget($cache_names);
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
        $shipping_addresses = DB::table('customer_address')
        ->select('address','cities.citymunDesc as city','countries.id as country_id')
        ->join('countries','countries.country_code','=','customer_address.country_code')
        ->join('cities','cities.citymunCode','=','customer_address.city_code')
        ->where('customer_id',$this->id)
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
        return !empty($this->customer_classification) ? $this->customer_classification->name : '';
    }

    public function getCustomerCodeAttribute()
    {
        $data = CustomerCode::find($this->customer_code_id);

        return !!$data ? $data : '';
    }

    public function customer_files()
    {
        return $this->hasMany(CustomerFile::class, 'customer_id');
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
        return $this->hasMany(CustomerAddress::class,'customer_id')->where('type_address', 'shipping') ?? [];
    }

    public function customer_billing_address()
    {
        return $this->hasMany(CustomerAddress::class,'customer_id')->where('type_address', 'billing') ?? [];
    }

    public function customer_print_templates()
    {
        return $this->hasMany(CustomerPrintTemplate::class, 'customer_id')->with('print_template');
    }

    public function default_print_template()
    {
        return $this->hasOne(CustomerPrintTemplate::class, 'customer_id')->where('is_default', 1)->with('print_template');
    }

}
