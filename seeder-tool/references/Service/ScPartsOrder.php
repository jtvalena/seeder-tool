<?php

namespace App\Models\Service;

use App\Models\PartsTransfer;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Service\ScRepairForm;
use App\Models\Service\ScRfEstimation;
use App\Models\User;

class ScPartsOrder extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['status','ready_to_serve', 'reason_for_requote', 'picker_personnel_id', 'checker_personnel_id', 'follow_up_remarks'];

    public function sc_repair_form()
    {
        return $this->belongsTo(ScRepairForm::class);
    }

    public function sc_parts_order_items()
    {
        return $this->hasMany(ScPartsOrderItem::class, 'sc_parts_order_id');
    }

    public function sc_rf_estimation()
    {
        return $this->belongsTo(ScRfEstimation::class);
    }
    public function parts_transfer()
    {
        return $this->hasOne(PartsTransfer::class,'sc_parts_order_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function picker()
    {
        return $this->belongsTo(Personnel::class, 'picker_personnel_id');
    }

    public function checker()
    {
        return $this->belongsTo(Personnel::class, 'checker_personnel_id');
    }
}
