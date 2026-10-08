<?php

namespace App\Models\Sales;

use App\Models\User;
use App\Traits\Caching;
use App\Models\Items\ItemBrand;
// use Illuminate\Support\Facades\DB;
use App\Models\MasterData\Customer;
use App\Models\MasterData\Salesman;
use App\Models\PackingListMovement;
use App\Models\Operations\StockCard;
use App\Models\Operations\Warehouse;
use Illuminate\Support\Facades\Auth;
use App\Models\Sales\PackingListItem;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Sales\PackingListPrintHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackingList extends Model implements Auditable
{
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        'customer_name', 'salesman_name', 'issuer_name', 'status_text', 'override_by_name', 'served_amount', 'wp_list_status', 'customer_po_num',
        'delivery_method_text', 'promo_name', 'warehouse_code', 'customer_print_with_price', 'customer_print_no_price', 'customer_print_srp', 'stock_card_status_text',
        'canceled_type_text',
    ];

    protected $fillable = [
        'order_num',
        'customer_id',
        'salesman_id',
        'user_id',
        'remarks',
        'total_amount',
        'discounts',
        'terms',
        'convert_date',                 //date when this is converted from SO to PL
        'status',
        'shipping_address_text',
        'shipping_address_id',
        'override_by',
        'discount_amount',
        'gross_amount',
        'checked_by',
        'prepared_by',
        'prepared_by_2',
        'noted_by',
        'submitted_by',
        'confirmed_by',
        'completed_by',
        'deleted_by',
        'submit_date',
        'confirm_date',
        'confirm_remarks',
        'receive_date',
        'received_by',
        'complete_date',
        'so_id',
        'printed',
        'print_with_price_counter',
        'print_without_price_counter',
        'special',
        'warehouse_id',
        'packing_list_date',
        'checker_form_id',
        'stock_card',
        'delivery_method',
        'promo_id',
        'packing_list_id',
        'approve_pl',
        'approved_by',
        'approved_date',
        'item_type_id',
        'stock_card_status',
        'trade',
        'dispatch_id',
        'canceled_type',
        'cancel_remarks',
        'canceled_by',
        'cancel_date',
        'brand_id',
        'pullout_customer_order',
        'wpf_id',
        'service',
    ];

    public function packing_list_items()
    {
        return $this->hasMany(PackingListItem::class, 'packing_list_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
        // return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'user_id');
        // return $this->belongsTo(User::class, 'user_id')->withTrashed();
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

    public function sales_orders()
    {
        // return $this->belongsTo(SalesOrder::class, 'so_id')->withTrashed();
        return $this->belongsTo(SalesOrder::class, 'so_id');
    }

    public function promo()
    {
        // return $this->belongsTo(Promo::class, 'promo_id')->withTrashed();
        return $this->belongsTo(Promo::class, 'promo_id');
    }

    public function warehouse()
    {
        // return $this->belongsTo(Warehouse::class, 'warehouse_id')->withTrashed();
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function brand()
    {
        return $this->belongsTo(ItemBrand::class, 'brand_id');
    }

    public function stock_cards()
    {
        return $this->hasMany(StockCard::class, 'packing_list_id');
    }

    public function print_histories()
    {
        return $this->hasMany(PackingListPrintHistory::class);
    }

    /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();

        $cache_names = [
            'all_customer_with_pl_selection',
            'packing_list_num_selection',
            'customer_items'
        ];
        static::created(function ($model) {
            PackingListMovement::create([
                'packing_list_id' => $model->id,
                'status' => $model->status,
                'updated_by' => $model->user_id,
            ]);
        });
        static::creating(function ($model) use($cache_names) {
            $model->forget($cache_names);
        });

        static::updated((function ($model) use($cache_names) {
            if($model->wasChanged('status')){
                PackingListMovement::create([
                    'packing_list_id' => $model->id,
                    'status' => $model->status,
                    'updated_by' => !empty(\Auth::id()) ? \Auth::id() : 0,
                ]);
            }
            $model->forget($cache_names);
        }));

        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        // static::creating(function ($model) {
        //     // $latest = PackingList::all()->last();
        //     $latest = PackingList::select('packing_list_num, order_num')->whereRaw('length(packing_list_num)=6')->max('packing_list_num');
        //     if (!empty($latest)) {
        //         $next_packing_list_num = ($latest != null) ? intval($latest) + 1 : 1;
        //         $model->packing_list_num = str_pad((string)$next_packing_list_num, 6, '0', STR_PAD_LEFT);
        //     } else {
        //         $model->packing_list_num = str_pad('1', 6, '0', STR_PAD_LEFT);
        //     }

        //     $model->order_num = 0;
        //     if (!empty($model->so_id)) {
        //         $so = DB::table('sales_orders')->select('order_num')->where('id', $model->so_id)->first();
        //         if (!empty($so)) {
        //             $model->order_num = $so->order_num;
        //         }
        //     }
        // });
    }

    public function getCustomerNameAttribute()
    {
        $customer_name = "";
        if (!empty($this->customer)) {
            if ($this->customer->type == 'bus') {
                $customer_name = !empty($this->customer->company_name) ? $this->customer->company_name : '';
            } else {
                $customer_name = !empty($this->customer->full_name) ? $this->customer->full_name : '';
            }
        }
        unset($this->customer);
        return $customer_name;
    }

    public function getSalesmanNameAttribute()
    {
        $salesman_name = !empty($this->salesman) ? $this->salesman->name : '';
        unset($this->salesman);
        return $salesman_name;
    }

    public function getIssuerNameAttribute()
    {
        $issuer_name = !empty($this->issuer) ? $this->issuer->name : '';
        unset($this->issuer);
        return $issuer_name;
    }

    public function getCheckedByNameAttribute()
    {
        $checked_by_name = !empty($this->checked_by_user) ? $this->checked_by_user->name : '';
        unset($this->checked_by_user);
        return $checked_by_name;
    }

    public function getPreparedByNameAttribute()
    {
        $prepared_by_name = !empty($this->prepared_by_user) ? $this->prepared_by_user->name : '';
        unset($this->prepared_by_user);
        return $prepared_by_name;
    }

    public function getPreparedByName2Attribute()
    {
        $prepared_by_name_2 = !empty($this->prepared_by_user_2) ? $this->prepared_by_user_2->name : '';
        unset($this->prepared_by_user_2);
        return $prepared_by_name_2;
    }

    public function getNotedByNameAttribute()
    {
        $noted_by_name = !empty($this->noted_by_user) ? $this->noted_by_user->name : '';
        unset($this->noted_by_user);
        return $noted_by_name;
    }

    public function getOverrideByNameAttribute()
    {
        $override_by_name = !empty($this->override_by_user) ? $this->override_by_user->name : '';
        unset($this->override_by_user);
        return $override_by_name;
    }

    public function getServedAmountAttribute()
    {
        $amount = 0;
        foreach ($this->packing_list_items as $item) {
            $amount += $item->quantity_served * $item->amount;
        }
        //unset($this->packing_list_items);
        return $amount;
    }

    public function getWpListStatusAttribute()
    {
        $status = !empty($this->wp_list) ? $this->wp_list->status : '';
        unset($this->wp_list);
        return $status;
    }

    public function getCustomerPoNumAttribute()
    {
        $customerPONum = !empty($this->sales_orders) ? $this->sales_orders->customer_po_num : '';
        unset($this->sales_orders);
        return $customerPONum;
    }

    public function getCustomerPrintWithPriceAttribute()
    {
        $printWithPrice = !empty($this->customer) ? $this->customer->print_with_price : 0;
        unset($this->customer);
        return $printWithPrice;
    }

    public function getCustomerPrintNoPriceAttribute()
    {
        $printNoPrice = !empty($this->customer) ? $this->customer->print_no_price : 0;
        unset($this->customer);
        return $printNoPrice;
    }

    public function getCustomerPrintSrpAttribute()
    {
        $printSrp = !empty($this->customer) ? $this->customer->print_srp : 0;
        unset($this->customer);
        return $printSrp;
    }

    public function getPromoNameAttribute()
    {
        $name = !empty($this->promo) ? $this->promo->name : '';
        unset($this->promo);
        return $name;
    }

    public function getWarehouseCodeAttribute()
    {
        return $this->warehouse?->code ?? '';
    }

    public function getStatusTextAttribute()
    {
        /*
            0 = 'pending'
            1 = 'confirmed'
            2 = 'rejected'
            3 = 'canceled'
            4 = 'deleted'
            5 = 'submitted'
        */

        if ($this->status == 0) {
            return 'pending';
        } elseif ($this->status == 1) {
            return 'confirmed';
        } elseif ($this->status == 2) {
            return 'rejected';
        } elseif ($this->status == 3) {
            return 'canceled';
        } elseif ($this->status == 4) {
            return 'deleted';
        } elseif ($this->status == 5) {
            return 'submitted';
        } elseif ($this->status == 6) {
            return 'for approval';
        } elseif ($this->status == 7) {
            return 'approved';
        }
    }

    public function getDeliveryMethodTextAttribute()
    {
        /*
            0 = 'none'
            1 = 'pick-up'
            2 = 'delivery'
        */

        if ($this->delivery_mthod == 0) {
            return 'none';
        } elseif ($this->delivery_mthod == 1) {
            return 'pick-up';
        } elseif ($this->delivery_mthod == 2) {
            return 'delivery';
        }
    }

    public function getStockCardStatusTextAttribute() {
        if($this->stock_card_status == 1) {
            return 'pending';
        } else if ($this->stock_card_status == 2) {
            return 'partially serve';
        } else if ($this->stock_card_status == 3) {
            return 'canceled';
        } else if ($this->stock_card_status == 4) {
            return 'completed';
        } else {
            return null;
        }
    }

    public function getCanceledTypeTextAttribute() {
        if($this->canceled_type == 0) {
            return 'Cancel PL';
        } else if ($this->canceled_type == 1) {
            return 'Change PL Date';
        }
    }
}
