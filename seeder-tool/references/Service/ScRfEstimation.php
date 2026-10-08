<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sales\SalesQuotation;
use App\Models\Service\ScRepairForm;
use App\Models\Service\ScRfEstItem;
use App\Models\Service\ScPartsOrder;
use App\Models\Service\ScRfEstConversation;

class ScRfEstimation extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sc_repair_form_id',
        'sales_quotation_id',
        'total_amount',
        'status',
        'auto_foc',
        'printed_sq',
        'cancelled_at',
        'approved_at',
    ];

    protected $appends = [
        'sales_quotation_number',
    ];

    public function sc_repair_form(){
        return $this->belongsTo(ScRepairForm::class);
    }

    public function sc_rf_est_items(){
        return $this->hasMany(ScRfEstItem::class, 'sc_rf_estimation_id');
    }

    public function sc_parts_order(){
        return $this->hasOne(ScPartsOrder::class, 'sc_rf_estimation_id');
    }

    public function sc_rf_est_conversations(){
        return $this->hasMany(ScRfEstConversation::class, 'sc_rf_estimation_id');
    }
    public function sales_quotation_item() {
        return $this->hasOne(SalesQuotationItem::class, 'sc_rf_est_item_id', 'id');
    }

    public function sales_quotation()
    {
        return $this->belongsTo(SalesQuotation::class, 'sales_quotation_id');
    }

    public function getSalesQuotationNumberAttribute(): ?string
    {
        return $this->relationLoaded('sales_quotation')
            ? ($this->sales_quotation?->quote_num)
            : null;
    }
}
