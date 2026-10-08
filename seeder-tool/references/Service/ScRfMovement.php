<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service\ScRepairForm;
use App\Models\User;

class ScRfMovement extends Model
{
    use HasFactory;

    public function sc_repair_form(){
        return $this->belongsTo(ScRepairForm::class);
    }

    public function updated_by(){
        return $this->hasOne(User::class, 'id', 'updated_by_id');
    }
}
