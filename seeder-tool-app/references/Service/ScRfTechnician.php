<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Service\ScRepairForm;

class ScRfTechnician extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sc_repair_form() {
        return $this->belongsTo(ScRepairForm::class);
    }
}
