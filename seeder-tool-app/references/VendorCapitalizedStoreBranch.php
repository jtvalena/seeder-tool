<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCapitalizedStoreBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_capitalized_company_id',
        'store_branch_id',
    ];

    public function capitalized_comapnies(){
        return $this->belongsTo(VendorCapitalizedCompany::class,'id');
    }
}
