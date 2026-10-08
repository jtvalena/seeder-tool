<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminTransmittalItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_transmittal_id',
        'company_id',
        'store_branch_id',
    ];

    public function adminTransmittal() {
        return $this->belongsTo(AdminTransmittal::class);
    }

    public function adminTransmittalPurchaseOrders() {
        return $this->hasMany(AdminTransmittalJobOrder::class);
    }
}
