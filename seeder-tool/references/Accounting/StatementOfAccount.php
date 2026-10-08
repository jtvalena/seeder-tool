<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MasterData\Customer;
use App\Models\Accounting\StatementOfAccountItem;
use App\Models\User;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\ItemBrand;
use App\Models\MasterData\Salesman;
use App\Models\StatementOfAccountItemsAdmin;
use App\Traits\Caching;
use App\Models\Accounting\CreditMemo;
use App\Models\Accounting\CreditMemoAllocation;
use App\Models\Accounting\DebitMemo;
use App\Models\Accounting\DebitMemoAllocation;
use Illuminate\Support\Facades\DB;

class StatementOfAccount extends Model implements Auditable
{
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['customer_name', 'customer_address', 'status_text', 'completed_by_name' ,'override_by_name',
                'approved_by_name', 'prepared_by_name', 'payment_method_text', 'all_shipping_addresses','salesman_name',
                'period', 'overdue', 'received_by_text', 'brand_text',
            ];

    protected $fillable = [
        'customer_id',
        'contact',
        'remarks',
        'due_date',
        'terms',
        'status',
        'paid',
        'prepared_by',
        'approved_by',
        'completed_by',
        'override_by',
        'approve_date',
        'complete_date',
        'selected_month',
        'selected_year',
        'payment_method',
        'paid_amount',
        'total_amount',
        'gross_amount',
        'discount_amount',
        'addition_amount',
        'addition_remarks',
        'deduction_amount',
        'deduction_remarks',
        'payment_date',
        'bad_debt_amount',
        'received_at',
        'received_by',
        'printed',
        'reprint',
        'number_of_pages',
        'reason_for_revert',
        'brand_id',
    ];

    public function getDueMonthAttribute(){
        return !empty($this->due_date)?Carbon::createFromFormat('Y-m', $this->due_date):null;
    }

    public function statement_of_account_items()
    {
        return $this->hasMany(StatementOfAccountItem::class);
    }

    public function statement_of_account_items_admins()
    {
        return $this->hasMany(StatementOfAccountItemsAdmin::class);
    }

    public function customer()
    {
        // return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function prepared_by_user()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function completed_by_user()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function override_by_user()
    {
        // return $this->belongsTo(User::class, 'override_by')->withTrashed();
        return $this->belongsTo(User::class, 'override_by');
    }

    public function approved_by_user()
    {
        // return $this->belongsTo(User::class, 'approved_by')->withTrashed();
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function brand()
    {
        return $this->belongsTo(ItemBrand::class, 'brand_id');
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

    public function getCustomerAddressAttribute(){
        $address = "";
        if(!empty($this->customer->address)){
            $address = $this->customer->address;
        }
        unset($this->customer);
        return $address;
    }

    public function getPreparedByNameAttribute(){
        $name = !empty($this->prepared_by_user) ? $this->prepared_by_user->name : '';
        unset($this->prepared_by_user);
        return $name;
    }

    public function getCompletedByNameAttribute(){
        $name = !empty($this->completed_by_user) ? $this->completed_by_user->name : '';
        unset($this->completed_by_user);
        return $name;
    }

    public function getOverrideByNameAttribute(){
        $override_by_name = !empty($this->override_by_user) ? $this->override_by_user->name : '';
        unset($this->override_by_user);
        return $override_by_name;
    }

    public function getApprovedByNameAttribute(){
        $name = !empty($this->approved_by_user) ? $this->approved_by_user->name : '';
        unset($this->approved_by_user);
        return $name;
    }

    public function getBrandTextAttribute(){
        $brand_text = '';
        if (!empty($this->brand_id)) {
            $brand_text = !empty($this->brand) ? $this->brand->name : '';
        }
        unset($this->brand);

        return $brand_text;
    }

    /*public function getGrandTotalAttribute(){
        $grand_total = 0;
        if(!empty($this->statement_of_account_items)){
            foreach($this->statement_of_account_items as $soa_item){
                $final_amount = !empty(floatval($soa_item->override_amount))?$soa_item->override_amount:$soa_item->sales_total;
                $grand_total += floatval($final_amount);
            }
        }

        return floatval($grand_total);
    }*/

    public function getStatusTextAttribute()
    {
        // submitted for approval (approved, rejected, cancelled)
        // confirmed final
        if($this->status==0){
            return 'saved';
        }elseif($this->status==1){
            return 'approved';
        }elseif($this->status==2){ // -
            return 'rejected';
        }elseif($this->status==3){
            return 'canceled';
        }elseif($this->status==4){ // -
            return 'deleted';
        }elseif($this->status==5){ // -
            return 'confirmed';
        }elseif($this->status==6){
            return 'partial (o)';
        }elseif($this->status==7){
            return 'paid';
        }elseif($this->status==8){
            return 'overdue';
        }elseif($this->status==9){
            return 'partial (u)';
        }
    }

    public function getPaymentMethodTextAttribute()
    {
        if($this->payment_method==1){
            return 'cash';
        }elseif($this->payment_method==2){
            return 'cheque';
        }elseif($this->payment_method==3){
            return 'deposit';
        }
    }

    public function getAllShippingAddressesAttribute()
    {

        $address_options = [];
        // $shipping_addresses = !empty($this->shipping_addresses) ? json_decode($this->shipping_addresses, TRUE) : "";
        $shipping_addresses = DB::table('customer_address')
        ->select('address','cities.citymunDesc as city','countries.id as country_id')
        ->join('countries','countries.country_code','=','customer_address.country_code')
        ->join('cities','cities.citymunCode','=','customer_address.city_code')
        ->where('customer_id',$this->customer_id)
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

    public function getSalesmanNameAttribute(){
        $salesman_name = "";
        if(!empty($this->salesman_id)){
            return Salesman::withTrashed()->find($this->salesman_id)->value('name');
        }else{
            return '';
        }
    }

    public function getPeriodAttribute()
    {
        $month = $this->selected_month;
        $year = $this->selected_year;

        $date = \Carbon\Carbon::createFromDate($year, $month, 1);

        return $date->format('M Y');
    }

    public function getOverdueAttribute()
    {
        $currentDate = Carbon::now();
        $dueDate = Carbon::parse($this->due_date);

        if($currentDate < $dueDate){
            $daysDifference = 0;
        }
        else{
        $daysDifference = $dueDate->diffInDays($currentDate, false);
        }

        return $daysDifference;
    }

    public function getReceivedByTextAttribute()
    {
        $name = User::where('id', $this->received_by)->value('name');

        if($name) {
            return $name;
        } else {
            return '';
        }
    }

    /**
     * Always return balance_amount rounded to 2 decimal places to prevent
     * floating-point residues (e.g. 0.00000000000233) from surfacing in the UI.
     */
    public function getBalanceAmountAttribute(): float
    {
        return round((float) ($this->attributes['balance_amount'] ?? 0), 2);
    }

    /** Reverse relationship to active allocation rows for this SOA. */
    public function creditMemoAllocations()
    {
        return $this->hasMany(CreditMemoAllocation::class, 'soa_id');
    }

    /** Reverse relationship to active DM allocation rows for this SOA. */
    public function debitMemoAllocations()
    {
        return $this->hasMany(DebitMemoAllocation::class, 'soa_id');
    }

    /**
     * Recompute addition_amount, deduction_amount, total_amount, and balance_amount.
     *
     * Both addition and deduction are now sourced exclusively from their respective
     * allocation tables (debit_memo_allocations and credit_memo_allocations), which are
     * the canonical sources of truth after the migration from deprecated soa_id columns.
     *
     * addition_amount  = sum of debit_memo_allocations.allocated_amount (soa_id = this)
     * deduction_amount = sum of credit_memo_allocations.allocated_amount (soa_id = this)
     * total_amount     = gross_amount + addition_amount − deduction_amount − discount_amount
     * balance_amount   = total_amount − paid_amount
     */
    public function recompute(): void
    {
        // Use the allocation table as the single source of truth for DM additions.
        $new_addition_amount = DebitMemoAllocation::where('soa_id', $this->id)
            ->whereNull('deleted_at')
            ->sum('allocated_amount');

        // Use the allocation table as the single source of truth for CM deductions.
        // Soft-deleted rows (cancelled/rejected allocations) are automatically excluded.
        $new_deduction_amount = CreditMemoAllocation::where('soa_id', $this->id)
            ->whereNull('deleted_at')
            ->sum('allocated_amount');

        $new_total_amount = round(
            floatval($this->gross_amount)
                + floatval($new_addition_amount)
                - floatval($new_deduction_amount)
                - floatval($this->discount_amount),
            2
        );

        $new_balance_amount = round(
            floatval($new_total_amount) - floatval($this->paid_amount),
            2
        );

        $this->addition_amount  = round(floatval($new_addition_amount), 2);
        $this->deduction_amount = round(floatval($new_deduction_amount), 2);
        $this->total_amount     = $new_total_amount;
        $this->balance_amount   = $new_balance_amount;
        $this->update();
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'statement_of_account_selection',
        ];
        static::creating(function ($model) use($cache_names) {
            $model->forget($cache_names);
        });

        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));

        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }

}
