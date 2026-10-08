<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScRfLatestMovement extends Model
{

    protected $fillable = [
        'sc_repair_form_id',
        'days',
        'status',
        'sc_rf_movement_id'
    ];
}
