<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Service\ScRepairForm;

class ScRepairFormFile extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
    protected $appends = ['uploaded_by_name'];
    
    protected $fillable = [
        'name',
        'path',
    ];

    public function sc_repair_form() {
        return $this->belongsTo(ScRepairForm::class);
    }

    public function getUploadedByNameAttribute(){
        return $this->uploaded_by;
    }
}
