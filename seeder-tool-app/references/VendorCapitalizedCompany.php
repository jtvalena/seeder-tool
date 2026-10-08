<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCapitalizedCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'company_id',
        'created_by',
    ];

    public function capitalized_store_branches(){
        return $this->hasMany(VendorCapitalizedStoreBranch::class,'vendor_capitalized_company_id');
    }
}
