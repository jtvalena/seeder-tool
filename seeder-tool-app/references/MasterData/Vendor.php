<?php

namespace App\Models\MasterData;

use App\Models\VendorPaymentItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
// use App\Models\User;
use App\Models\VendorCapitalizedCompany;
use App\Traits\Caching;
// use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Vendor extends Model implements Auditable
{
    use HasFactory, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'vendor_type_id',
        'vat_type_id',
        // 'vendor_name',
        'account_number,
        print_on_cheque_as',
        'company_name',
        'company_address',
        'billing_address',
        'ship_from',
        'payee',
        'balance_total',
        'active',
        'created_by',
        'updated_by',
        'deleted_by',
        'terms',
        'is_broker',
        'affiliate_broker_id',
        'first_name',
        'last_name',
        'middle_name',
        'type',
        'origin',
    ];
    protected $appends = [
        'all_shipping_addresses',
        'vendor_address',
        'payments',
        // 'coa_details',
        'capitalize',
    ];

    // protected $appends = [ 'created_by_name', 'updated_by_name', 'deleted_by_name'];
    public function getAllShippingAddressesAttribute()
    {

        $address_options = [];
        // $shipping_addresses = !empty($this->shipping_addresses) ? json_decode($this->shipping_addresses, TRUE) : "";
        $shipping_addresses = DB::table('vendor_address')
        ->select('address','cities.citymunDesc as city','countries.id as country_id','vendor_address.id')
        ->join('countries','countries.country_code','=','vendor_address.country_code')
        ->join('cities','cities.citymunCode','=','vendor_address.city_code')
        ->where('customer_id',$this->id)
        ->get();
        // if (!empty($shipping_addresses)) {

            foreach ($shipping_addresses as $key => $shipping_address) {
                $country =  DB::table('countries')->select('iso_3166_2')->where('country_code', $shipping_address->country_id)->first();
                $address =  !empty($shipping_address->address) ? $shipping_address->address . ', ' : "";
                $city    =  !empty($shipping_address->city) ? $shipping_address->city . ', ' : "";

                $address_options[$key]['value'] = $shipping_address->id;
                $address_options[$key]['text'] = $address . $city . $country->iso_3166_2;
            }
        // }

        return $address_options;
    }

    public function getVendorAddressAttribute(){
        $vendor_addresses = DB::table('vendor_address')
            ->select(
                'vendor_address.id',
                'vendor_address.customer_id',
                'vendor_address.country_code',
                'countries.full_name as country_name',
                'vendor_address.region_code',
                'regions.regDesc as region_name',
                'vendor_address.province_code',
                'provinces.provDesc as province_name',
                'vendor_address.city_code',
                'cities.citymunDesc as city_name',
                'vendor_address.address',
                'vendor_address.contact_person',
                'vendor_address.contact_number',
                'vendors.company_name',
                'vendors.company_name',
                'vendors.last_name',
                'vendors.first_name',
                'vendors.middle_name',
            )
            ->leftJoin('vendors', 'vendor_address.customer_id', 'vendors.id')
            ->leftJoin('countries', 'vendor_address.country_code', 'countries.id')
            ->leftJoin('regions', 'vendor_address.region_code', 'regions.id')
            ->leftJoin('provinces', 'vendor_address.province_code', 'provinces.id')
            ->leftJoin('cities', 'vendor_address.city_code', 'cities.id')
            ->where('customer_id',$this->id)
            ->get();

        return $vendor_addresses;
    }

    public function getPaymentsAttribute(){
        $payments = VendorPaymentItem::select(
                'vendors.company_name',
                'vendors.last_name',
                'vendors.first_name',
                'vendors.middle_name',
                'vendor_payment_items.id',
                'vendor_payment_items.payee',
                'vendor_payment_items.account_num',
                'vendor_payment_items.terms',
                'payment_types.name as payment_type_name',
                'vendor_payment_items.payment_types_id'
            )
            ->leftJoin('vendors', 'vendor_payment_items.vendor_id', 'vendors.id')
            ->leftJoin('payment_types', 'vendor_payment_items.payment_types_id', 'payment_types.id')
            ->where('vendor_id',$this->id)
            ->where('vendor_payment_items.active', 1)
            ->get();
        return $payments;
    }

    public function getCapitalizeAttribute(){
        $payments = VendorCapitalizedCompany::select(
                'vendors.company_name',
                'vendors.last_name',
                'vendors.first_name',
                'vendors.middle_name',
                'vendor_capitalized_companies.id',
                'vendor_capitalized_companies.company_id',
                'vendor_capitalized_store_branches.id as vendor_capitalized_store_branches_id',
                'vendor_capitalized_store_branches.store_branch_id',
            )
            ->leftJoin('vendors', 'vendor_capitalized_companies.vendor_id', 'vendors.id')
            ->leftJoin('vendor_capitalized_store_branches', 'vendor_capitalized_store_branches.vendor_capitalized_company_id', 'vendor_capitalized_companies.id')
            ->leftJoin('companies', 'companies.id', 'vendor_capitalized_companies.company_id')
            ->where('vendor_capitalized_companies.vendor_id',$this->id)
            ->get();
        return $payments;
    }

    // public function getCoaDetailsAttribute(){
    //     $coa = DB::table('vendor_coa_items')
    //         ->select(
    //             'vendors.company_name',
    //             'vendors.company_name',
    //             'vendors.last_name',
    //             'vendors.first_name',
    //             'vendors.middle_name',
    //             'vendor_coa_items.id',
    //             'vendor_coa_items.vendor_id',
    //             'vendor_coa_items.coa_id',
    //             'vendor_coa_items.particulars',
    //             'vendor_coa_items.debit_amount',
    //             'vendor_coa_items.credit_amount',
    //             'vendor_coa_items.id as vendor_coa_id',
    //             DB::raw('IFNULL(DATE_FORMAT(recognition_month, "%Y-%m"), "' . Carbon::now() . '") as recognition_month'),
    //             'vendor_coa_items.department_id',
    //             'vendor_coa_items.job_id',
    //             'chart_of_accounts.account_name',
    //             'departments.name as department_name',
    //             'accounting_jobs.name as job_name',
    //         )
    //         ->where('vendor_id',$this->id)
    //         ->leftJoin('vendors','vendor_coa_items.vendor_id','vendors.id')
    //         ->leftJoin('chart_of_accounts','vendor_coa_items.coa_id','chart_of_accounts.id')
    //         ->leftJoin('departments','vendor_coa_items.department_id','departments.id')
    //         ->leftJoin('accounting_jobs','vendor_coa_items.job_id','accounting_jobs.id')
    //         ->whereNull('vendor_coa_items.deleted_at')
    //         ->get();

    //     return $coa;
    // }

    public function vendorUploads()
    {
        return $this->hasMany(VendorUpload::class);
    }

    // protected $fillable = [
    //     'code',
    //     'name',
    //     'description',
    //     'created_by',
    //     'updated_by',
    //     'deleted_by',
    // ];

    // public function created_by_user()
    // {
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    // public function updated_by_user()
    // {
    //     return $this->belongsTo(User::class, 'updated_by');
    // }

    // public function deleted_by_user()
    // {
    //     return $this->belongsTo(User::class, 'deleted_by');
    // }

    // public function getCreatedByNameAttribute()
    // {
    //     return $this->created_by_user->name;
    // }

    // public function getUpdatedByNameAttribute()
    // {
    //     return $this->updated_by_user->name;
    // }

    // public function getDeletedByNameAttribute()
    // {
    //     return $this->deleted_by_user->name ?? '';

    // }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'admin_vendor_selection',
            'admin_vendor_selection_broker',
            'vendors',
            'jo_vendor_details',
            'admin_purchase_order',
            'vendor_payment_items_selection',
            'capital_provider_vendor_selection',
        ];
        static::created((function ($model) use($cache_names) {
            $last_priority = Vendor::whereNotNull('priority')->max('priority');
            $find_vendor = Vendor::find($model->id);
            $find_vendor->priority = $last_priority + 1;
            $find_vendor->update();
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }
}
