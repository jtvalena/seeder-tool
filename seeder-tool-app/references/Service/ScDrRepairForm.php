<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Service\ScRepairForm;
use App\Models\Service\ScDelivery;

class ScDrRepairForm extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function sc_delivery()
    {
        return $this->belongsTo(ScDelivery::class);
    }

    public function sc_repair_form() {
        return $this->belongsTo(ScRepairForm::class);
    }
}
