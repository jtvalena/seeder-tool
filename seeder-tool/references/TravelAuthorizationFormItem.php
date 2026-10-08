<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TravelAuthorizationFormItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'taf_id',
        // 'travel_date',
        // 'origin',
        // 'destination',
        'travel_authorization_form_template_list_id',
        // 'detailed_purpose',
        // 'amount',
        'key_1', 
        'key_2', 
        'key_3', 
        'key_4', 
        'key_5', 
        'key_6', 
        'key_7', 
        'key_8', 
        'key_9', 
        'key_10', 
        'key_11', 
        'key_12', 
        'key_13', 
        'key_14', 
        'key_15', 
        'key_16', 
        'key_17', 
        'key_18', 
        'key_19', 
        'key_20',
        'payment_type',
        'payment_term',
        'item_amount_to_pay',
        'item_paid_amount',
        'approved_amount',
        'foc',
        'amount',
        'paid',
        'bank_card_id',
        'petty_cash_budget_item_id',
    ];
    public function default_value_items()
    {
        return $this->hasMany(DefaultValueItem::class, 'taf_item_id');
    }
    public function templateList()
    {
        return $this->belongsTo(TravelAuthorizationFormTemplateList::class, 'travel_authorization_form_template_list_id');
    }
    public function form()
    {
        return $this->belongsTo(TravelAuthorizationForm::class, 'taf_id');
    }
    public function enterBillItems()
    {
        return $this->hasMany(EnterBillItems::class, 'transact_item_id')
                    ->where('transact_type_id', TransactType::where('model_name', 'EmployeeTravelBudget')->value('id'));
    }
}
