<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFasSelected extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_fas_basket_id',
        'admin_fas_requisition_id',
    ];
}
